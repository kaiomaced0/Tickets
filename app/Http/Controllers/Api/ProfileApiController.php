<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Profile\ProfileUpdateService;
use App\Services\Profile\ProfileDeactivateService;
use App\Services\User\PasswordUpdateService;
use App\Services\User\PasswordResetService;
use App\Services\Settings\SettingsUpdateThemeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ProfileApiController extends Controller
{
    public function __construct(
        private readonly ProfileUpdateService $profileUpdateService,
        private readonly ProfileDeactivateService $profileDeactivateService,
        private readonly PasswordUpdateService $passwordUpdateService,
        private readonly PasswordResetService $passwordResetService,
        private readonly SettingsUpdateThemeService $settingsUpdateThemeService,
    ) {}

    /**
     * Retorna o perfil do usuário autenticado.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()));
    }

    /**
     * Atualiza o perfil do usuário autenticado.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $this->profileUpdateService->handle($request->user(), $validated);

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Atualiza a senha do usuário autenticado.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $this->passwordUpdateService->handle(
                $request->user(),
                $validated['current_password'],
                $validated['password'],
            );
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return response()->json(['message' => 'Senha atualizada com sucesso.']);
    }

    /**
     * Desativa a conta do usuário autenticado.
     */
    public function deactivate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        try {
            $this->profileDeactivateService->handle($request->user(), $validated['password']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Revoga o token da API
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Conta desativada com sucesso.']);
    }

    /**
     * Atualiza o tema do usuário autenticado.
     */
    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);

        $user = $this->settingsUpdateThemeService->handle($request->user(), $validated['theme']);

        return response()->json([
            'message' => 'Tema atualizado com sucesso.',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Solicita link de redefinição de senha (rota pública).
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = $this->passwordResetService->sendResetLink($request->email);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Link de redefinição de senha enviado para o e-mail.']);
        }

        return response()->json(['errors' => ['email' => [__($status)]]], 422);
    }

    /**
     * Reseta a senha usando o token (rota pública).
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = $this->passwordResetService->reset(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Senha redefinida com sucesso.']);
        }

        return response()->json(['errors' => ['email' => [__($status)]]], 422);
    }
}
