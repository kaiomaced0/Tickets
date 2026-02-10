<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListTicketsRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Services\Ticket\TicketCreateService;
use App\Services\Ticket\TicketDeleteService;
use App\Services\Ticket\TicketFilterService;
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
        private readonly TicketFilterService $filterService,
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
        $rawFilters = [
            'status' => $request->query('status'),
            'prioridade' => $request->query('prioridade'),
            'q' => $request->query('q'),
            'user_type' => $request->query('user_type'),
            'user_filter' => $request->query('user_filter'),
        ];

        $filters = $this->filterService->processWebFilters($rawFilters);
        $tickets = $this->listService->handle(Auth::user(), $filters, 15);
        $users = User::where('active', true)->orderBy('name')->get();

        return view('tickets', [
            'tickets' => $tickets,
            'users' => $users,
            'filters' => $rawFilters,
            'statuses' => ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'],
            'prioridades' => ['BAIXA', 'MEDIA', 'ALTA'],
        ]);
    }

    public function webCreate(): View
    {
        $users = User::where('active', true)->orderBy('name')->get();

        return view('tickets.create', [
            'users' => $users,
            'statuses' => ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'],
            'prioridades' => ['BAIXA', 'MEDIA', 'ALTA'],
        ]);
    }

    public function webStore(StoreTicketRequest $request)
    {
        $ticket = $this->createService->handle($request->validated(), $request->user());

        return redirect()->route('tickets.show-web', $ticket->id)
            ->with('success', 'Ticket criado com sucesso!');
    }

    public function webShow(int $ticket): View
    {
        $ticketModel = $this->showService->handle($ticket);
        $users = User::where('active', true)->orderBy('name')->get();

        return view('tickets.show', [
            'ticket' => $ticketModel,
            'users' => $users,
            'statuses' => ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'],
            'prioridades' => ['BAIXA', 'MEDIA', 'ALTA'],
        ]);
    }

    public function webUpdate(UpdateTicketRequest $request, int $ticket)
    {
        $ticketModel = $this->showService->handle($ticket);
        $updated = $this->updateService->handle($ticketModel, $request->validated());

        return redirect()->route('tickets.show-web', $updated->id)
            ->with('success', 'Ticket atualizado com sucesso!');
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
