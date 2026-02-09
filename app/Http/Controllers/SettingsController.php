<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
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

        $user = Auth::user();
        $user->theme = $request->theme;
        $user->save();

        return back()->with('status', 'theme-updated');
    }
}
