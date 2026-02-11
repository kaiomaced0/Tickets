<?php

namespace App\Http\Controllers;

use App\Services\Settings\SettingsUpdateThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsUpdateThemeService $updateThemeService,
    ) {
    }
    public function index(): View
    {
        return view('settings.index', [
            'user' => Auth::user(),
        ]);
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $this->updateThemeService->handle(Auth::user(), $request->theme);

        return back()->with('status', 'theme-updated');
    }
}
