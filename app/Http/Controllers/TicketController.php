<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListTicketsRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\Ticket\TicketCreateService;
use App\Services\Ticket\TicketDeleteService;
use App\Services\Ticket\TicketListService;
use App\Services\Ticket\TicketShowService;
use App\Services\Ticket\TicketUpdateStatusService;
use App\Services\Ticket\TicketUpdateService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    public function index(ListTicketsRequest $request): JsonResponse
    {
        $filters = $request->safe()->only(['status', 'prioridade', 'solicitante_id', 'responsavel_id', 'active', 'q']);
        $perPage = $request->validated('per_page');

        $tickets = $this->listService->handle(Auth::user(), $filters, $perPage);

        return response()->json([
            'data' => $tickets->items(),
            'meta' => [
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'filters' => $filters,
            ],
        ]);
    }

    public function webIndex(Request $request): View
    {
        $filters = [
            'status' => $request->query('status'),
            'prioridade' => $request->query('prioridade'),
            'q' => $request->query('q'),
            'user_type' => $request->query('user_type'), // 'solicitante' ou 'responsavel'
            'user_filter' => $request->query('user_filter'), // 'me' ou nome
        ];

        // Ajustar filtros baseado no tipo de usuário
        if (!empty($filters['user_type']) && !empty($filters['user_filter'])) {
            if ($filters['user_filter'] === 'me') {
                $filters[$filters['user_type'] . '_id'] = Auth::id();
            } else {
                // Buscar usuário por nome
                $user = User::where('name', 'like', '%' . $filters['user_filter'] . '%')->first();
                if ($user) {
                    $filters[$filters['user_type'] . '_id'] = $user->id;
                }
            }
        }

        $tickets = $this->listService->handle(null, array_filter($filters), 15);
        $users = User::where('active', true)->orderBy('name')->get();

        return view('tickets', [
            'tickets' => $tickets,
            'users' => $users,
            'filters' => $filters,
            'statuses' => ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'],
            'prioridades' => ['BAIXA', 'MEDIA', 'ALTA'],
        ]);
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
