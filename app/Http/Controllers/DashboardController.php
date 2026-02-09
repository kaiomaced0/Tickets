<?php

namespace App\Http\Controllers;

use App\Services\Ticket\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function __invoke(): JsonResponse
    {
        $user = Auth::user();
        $data = $this->dashboardService->handle($user);

        return response()->json($data);
    }
}
