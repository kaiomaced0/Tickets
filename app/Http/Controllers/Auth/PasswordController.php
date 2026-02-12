<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\PasswordUpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function __construct(
        private readonly PasswordUpdateService $passwordUpdateService,
    ) {}

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            $this->passwordUpdateService->handle(
                $request->user(),
                $validated['current_password'],
                $validated['password'],
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors(), 'updatePassword');
        }

        return back()->with('status', 'password-updated');
    }
}
