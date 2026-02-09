<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\Ticket\TicketCreateService;
use App\Services\Ticket\TicketDeleteService;
use App\Services\Ticket\TicketListService;
use App\Services\Ticket\TicketShowService;
use App\Services\Ticket\TicketUpdateStatusService;
use App\Services\Ticket\TicketUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketListService $listService,
        private readonly TicketCreateService $createService,
        private readonly TicketShowService $showService,
        private readonly TicketUpdateService $updateService,
        private readonly TicketDeleteService $deleteService,
        private readonly TicketUpdateStatusService $statusService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'prioridade', 'solicitante_id', 'responsavel_id', 'active', 'q']);
        $perPage = $request->integer('per_page');

        $tickets = $this->listService->handle(Auth::user(), $filters, $perPage ?: null);

        return response()->json($tickets);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->createService->handle($request->validated(), $request->user());

        return response()->json($ticket, 201);
    }

    public function show(int $ticket): JsonResponse
    {
        $ticketModel = $this->showService->handle($ticket);

        return response()->json($ticketModel);
    }

    public function update(UpdateTicketRequest $request, int $ticket): JsonResponse
    {
        $ticketModel = $this->showService->handle($ticket);
        $updated = $this->updateService->handle($ticketModel, $request->validated());

        return response()->json($updated);
    }

    public function updateStatus(UpdateTicketStatusRequest $request, int $ticket): JsonResponse
    {
        $ticketModel = $this->showService->handle($ticket);
        $updated = $this->statusService->handle($ticketModel, $request->validated('status'), $request->user());

        return response()->json($updated);
    }

    public function destroy(int $ticket): JsonResponse
    {
        $ticketModel = $this->showService->handle($ticket);
        $this->deleteService->handle($ticketModel, Auth::user());

        return response()->json([], 204);
    }
}
