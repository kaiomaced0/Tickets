<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserListService;
use App\Services\User\UserCreateService;
use App\Services\User\UserUpdateService;
use App\Services\User\UserToggleActiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct(
        private readonly UserListService $listService,
        private readonly UserCreateService $createService,
        private readonly UserUpdateService $updateService,
        private readonly UserToggleActiveService $toggleActiveService,
    ) {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'role', 'active']);
        $users = $this->listService->handle($filters);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:ADMIN,USER'],
            'active' => ['boolean'],
        ]);

        $validated['active'] = $request->boolean('active', true);

        $this->createService->handle($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:ADMIN,USER'],
            'active' => ['boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        $this->updateService->handle($user, $validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function toggleActive(User $user)
    {
        try {
            $updatedUser = $this->toggleActiveService->handle($user, Auth::user());

            return response()->json([
                'success' => true,
                'active' => $updatedUser->active,
                'message' => $updatedUser->active ? 'Usuário ativado com sucesso!' : 'Usuário desativado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
