<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserListService;
use App\Services\User\UserCreateService;
use App\Services\User\UserUpdateService;
use App\Services\User\UserToggleActiveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserApiController extends Controller
{
    public function __construct(
        private readonly UserListService $listService,
        private readonly UserCreateService $createService,
        private readonly UserUpdateService $updateService,
        private readonly UserToggleActiveService $toggleActiveService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'role', 'active']);
        $perPage = $request->input('per_page', 15);

        $users = $this->listService->handle($filters, $perPage);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'filters' => $filters,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:ADMIN,USER'],
            'active' => ['boolean'],
        ]);

        $validated['active'] = $request->boolean('active', true);

        $user = $this->createService->handle($validated);

        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:ADMIN,USER'],
            'active' => ['boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        $updated = $this->updateService->handle($user, $validated);

        return response()->json($updated);
    }

    public function toggleActive(User $user): JsonResponse
    {
        try {
            $updatedUser = $this->toggleActiveService->handle($user, Auth::user());

            return response()->json([
                'success' => true,
                'data' => $updatedUser,
                'message' => $updatedUser->active ? 'Usuário ativado com sucesso!' : 'Usuário desativado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
