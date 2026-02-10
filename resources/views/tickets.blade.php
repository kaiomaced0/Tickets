@php
    $statusLabels = [
        'ABERTO' => ['label' => 'Aberto', 'color' => 'text-blue-600 bg-blue-50 dark:text-blue-400 dark:bg-blue-950'],
        'EM_ANDAMENTO' => ['label' => 'Em andamento', 'color' => 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-950'],
        'RESOLVIDO' => ['label' => 'Resolvido', 'color' => 'text-green-600 bg-green-50 dark:text-green-400 dark:bg-green-950'],
        'CANCELADO' => ['label' => 'Cancelado', 'color' => 'text-neutral-600 bg-neutral-100 dark:text-neutral-400 dark:bg-neutral-900'],
    ];

    $prioridadeLabels = [
        'BAIXA' => ['label' => 'Baixa', 'color' => 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950'],
        'MEDIA' => ['label' => 'Média', 'color' => 'text-sky-700 bg-sky-50 dark:text-sky-400 dark:bg-sky-950'],
        'ALTA' => ['label' => 'Alta', 'color' => 'text-rose-700 bg-rose-50 dark:text-rose-400 dark:bg-rose-950'],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Tickets</h2>
            <p class="text-xs text-muted-foreground">Lista completa de solicitações e filtros</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Filtros -->
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                <div class="p-6">
                    <form method="GET" action="{{ route('tickets.list') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Busca de texto -->
                            <div class="lg:col-span-2">
                                <label for="q" class="block text-sm font-medium text-foreground mb-1">
                                    Buscar
                                </label>
                                <input
                                    type="text"
                                    id="q"
                                    name="q"
                                    value="{{ $filters['q'] ?? '' }}"
                                    placeholder="Título, descrição ou usuário..."
                                    class="w-full rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-primary"
                                />
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-foreground mb-1">
                                    Status
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    class="w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                >
                                    <option value="">Todos</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                            {{ $statusLabels[$status]['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Prioridade -->
                            <div>
                                <label for="prioridade" class="block text-sm font-medium text-foreground mb-1">
                                    Prioridade
                                </label>
                                <select
                                    id="prioridade"
                                    name="prioridade"
                                    class="w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                >
                                    <option value="">Todas</option>
                                    @foreach($prioridades as $prioridade)
                                        <option value="{{ $prioridade }}" {{ ($filters['prioridade'] ?? '') === $prioridade ? 'selected' : '' }}>
                                            {{ $prioridadeLabels[$prioridade]['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipo de usuário (Solicitante/Responsável) -->
                            <div>
                                <label for="user_type" class="block text-sm font-medium text-foreground mb-1">
                                    Filtrar por
                                </label>
                                <select
                                    id="user_type"
                                    name="user_type"
                                    class="w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                >
                                    <option value="">Nenhum filtro de usuário</option>
                                    <option value="solicitante" {{ ($filters['user_type'] ?? '') === 'solicitante' ? 'selected' : '' }}>
                                        Solicitante
                                    </option>
                                    <option value="responsavel" {{ ($filters['user_type'] ?? '') === 'responsavel' ? 'selected' : '' }}>
                                        Responsável
                                    </option>
                                </select>
                            </div>

                            <!-- Usuário -->
                            <div>
                                <label for="user_filter" class="block text-sm font-medium text-foreground mb-1">
                                    Usuário
                                </label>
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        id="user_filter"
                                        name="user_filter"
                                        value="{{ $filters['user_filter'] ?? '' }}"
                                        placeholder="Digite um nome ou 'me'"
                                        list="users-list"
                                        class="flex-1 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-primary"
                                    />
                                    <datalist id="users-list">
                                        <option value="me">Eu</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->name }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Digite "me" para seus tickets ou o nome de um usuário
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('tickets.create-web') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Novo Ticket
                            </a>
                            <div class="flex items-center gap-3">
                                <a
                                    href="{{ route('tickets.list') }}"
                                    class="inline-flex items-center px-4 py-2 rounded-lg border border-border bg-secondary text-foreground text-sm font-medium hover:bg-secondary/80 transition-colors"
                                >
                                    Limpar Filtros
                                </a>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-5 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors"
                                >
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="m21 21-4.35-4.35"/>
                                    </svg>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listagem de Tickets -->
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm overflow-hidden">
                @if($tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-muted/50 border-b border-border">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Título
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Prioridade
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Solicitante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Responsável
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                        Criado em
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($tickets as $ticket)
                                    <tr
                                        onclick="window.location='{{ route('tickets.show-web', $ticket->id) }}'"
                                        class="hover:bg-muted/30 transition-colors cursor-pointer"
                                    >
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-foreground">
                                                {{ $ticket->titulo }}
                                            </div>
                                            <div class="text-xs text-muted-foreground line-clamp-1">
                                                {{ $ticket->descricao }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusLabels[$ticket->status]['color'] }}">
                                                {{ $statusLabels[$ticket->status]['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeLabels[$ticket->prioridade]['color'] }}">
                                                {{ $prioridadeLabels[$ticket->prioridade]['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-medium">
                                                    {{ strtoupper(substr($ticket->solicitante->name, 0, 2)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm text-foreground">
                                                        {{ $ticket->solicitante->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-secondary/50 text-foreground flex items-center justify-center text-xs font-medium">
                                                    {{ strtoupper(substr($ticket->responsavel->name, 0, 2)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm text-foreground">
                                                        {{ $ticket->responsavel->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-muted-foreground">
                                            {{ $ticket->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="px-6 py-4 border-t border-border bg-muted/20">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-foreground">Nenhum ticket encontrado</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Tente ajustar os filtros ou criar um novo ticket.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
