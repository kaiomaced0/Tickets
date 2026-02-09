<?php

namespace App\Http\Controllers;

use App\Services\Ticket\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function __invoke(): View
    {
        $user = Auth::user();

        $data = $this->dashboardService->handle($user);

        return view('dashboard', [
            'user' => $user,
            'counts' => $data['counts'],
            'latestOpened' => $data['latest_opened'],
            'userInvolved' => $data['user_involved'],
        ]);
    }

    public function api(): JsonResponse
    {
        $user = Auth::user();
        $data = $this->dashboardService->handle($user);

        return response()->json($data);
    }
}
