@php
    $statusLabels = [
        'ABERTO' => ['label' => 'Aberto', 'color' => 'text-blue-600 bg-blue-50'],
        'EM_ANDAMENTO' => ['label' => 'Em andamento', 'color' => 'text-amber-600 bg-amber-50'],
        'RESOLVIDO' => ['label' => 'Resolvido', 'color' => 'text-green-600 bg-green-50'],
        'CANCELADO' => ['label' => 'Cancelado', 'color' => 'text-neutral-600 bg-neutral-100'],
    ];

    $prioridadeLabels = [
        'BAIXA' => ['label' => 'Baixa', 'color' => 'text-emerald-700 bg-emerald-50'],
        'MEDIA' => ['label' => 'Média', 'color' => 'text-sky-700 bg-sky-50'],
        'ALTA' => ['label' => 'Alta', 'color' => 'text-rose-700 bg-rose-50'],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Painel de Tickets</h2>
            <p class="text-xs text-muted-foreground">Visão geral dos tickets e atividades recentes</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @php
                $cards = [
                        [
                            'label' => 'Total',
                            'value' => $counts['total'] ?? 0,
                            'icon' => '<rect width="8" height="8" x="3" y="3" rx="2"/><path d="M7 11v4a2 2 0 0 0 2 2h4M11 7h4a2 2 0 0 1 2 2v4"/>',
                            'bg' => 'bg-primary/10 text-primary',
                            'url' => route('tickets.list')
                        ],
                        [
                            'label' => 'Abertos',
                            'value' => $counts['abertos'] ?? 0,
                            'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>',
                            'bg' => 'bg-blue-500/10 text-blue-400',
                            'url' => route('tickets.list', ['status' => 'ABERTO'])
                        ],
                        [
                            'label' => 'Em andamento',
                            'value' => $counts['em_andamento'] ?? 0,
                            'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                            'bg' => 'bg-amber-500/10 text-amber-400',
                            'url' => route('tickets.list', ['status' => 'EM_ANDAMENTO'])
                        ],
                        [
                            'label' => 'Resolvidos',
                            'value' => $counts['resolvidos'] ?? 0,
                            'icon' => '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/>',
                            'bg' => 'bg-green-500/10 text-green-400',
                            'url' => route('tickets.list', ['status' => 'RESOLVIDO'])
                        ],
                    ];

                    // Só adicionar card de Cancelados se houver cancelados
                    if (isset($counts['cancelados'])) {
                        $cards[] = [
                            'label' => 'Cancelados',
                            'value' => $counts['cancelados'],
                            'icon' => '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>',
                            'bg' => 'bg-neutral-500/15 text-neutral-300',
                            'url' => route('tickets.list', ['status' => 'CANCELADO'])
                        ];
                    }

                    // Define o número de colunas baseado na quantidade de cards
                    $gridCols = count($cards) === 5 ? 'lg:grid-cols-5' : 'lg:grid-cols-4';
                @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-4">
                @foreach ($cards as $card)
                    <a href="{{ $card['url'] }}" class="rounded-xl border border-border/50 bg-card/60 shadow-sm hover:shadow-md hover:border-border transition-all cursor-pointer">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-muted-foreground">{{ $card['label'] }}</p>
                                <p class="text-2xl font-semibold text-foreground mt-0.5">{{ $card['value'] }}</p>
                            </div>
                            <div class="h-10 w-10 rounded-lg {{ $card['bg'] }} flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    {!! $card['icon'] !!}
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 rounded-xl border border-border/50 bg-card/60 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6 border-b border-border/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-foreground">Últimos tickets abertos</h3>
                                <p class="text-sm text-muted-foreground">Últimos 6 registros</p>
                            </div>
                            <a href="{{ route('tickets.list', ['status' => 'ABERTO']) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors">
                                Ver todos
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-border/50">
                        @forelse ($latestOpened as $ticket)
                            <div
                                onclick="window.location='{{ route('tickets.show-web', $ticket->id) }}'"
                                class="p-5 flex flex-col gap-2 hover:bg-accent/10 transition-colors cursor-pointer"
                            >
                                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <span class="font-mono">#{{ $ticket->id }}</span>
                                    <span class="px-2 py-0.5 rounded-full {{ $statusLabels[$ticket->status]['color'] ?? 'bg-muted text-muted-foreground' }}">{{ $statusLabels[$ticket->status]['label'] ?? $ticket->status }}</span>
                                    <span class="px-2 py-0.5 rounded-full {{ $prioridadeLabels[$ticket->prioridade]['color'] ?? 'bg-muted text-muted-foreground' }}">{{ $prioridadeLabels[$ticket->prioridade]['label'] ?? $ticket->prioridade }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-foreground">{{ $ticket->titulo }}</h4>
                                        <p class="text-sm text-muted-foreground line-clamp-2">{{ $ticket->descricao }}</p>
                                    </div>
                                    <div class="text-right text-xs text-muted-foreground whitespace-nowrap">
                                        {{ $ticket->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground flex items-center gap-2">
                                    <span>Solicitante: <span class="text-foreground">{{ optional($ticket->solicitante)->name ?? 'N/I' }}</span></span>
                                    @if ($ticket->responsavel)
                                        <span class="text-muted-foreground">•</span>
                                        <span>Responsável: <span class="text-foreground">{{ $ticket->responsavel->name }}</span></span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <p class="text-sm text-muted-foreground">Nenhum ticket encontrado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6 border-b border-border/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-foreground">Meus tickets recentes</h3>
                                <p class="text-sm text-muted-foreground">Últimos 6 onde você está envolvido</p>
                            </div>
                            <a href="{{ route('tickets.list', ['q' => Auth::user()->name]) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors">
                                Ver todos
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-border/50">
                        @forelse ($userInvolved as $ticket)
                            <div
                                onclick="window.location='{{ route('tickets.show-web', $ticket->id) }}'"
                                class="p-5 flex flex-col gap-2 hover:bg-accent/10 transition-colors cursor-pointer"
                            >
                                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <span class="font-mono">#{{ $ticket->id }}</span>
                                    <span class="px-2 py-0.5 rounded-full {{ $statusLabels[$ticket->status]['color'] ?? 'bg-muted text-muted-foreground' }}">{{ $statusLabels[$ticket->status]['label'] ?? $ticket->status }}</span>
                                    <span class="px-2 py-0.5 rounded-full {{ $prioridadeLabels[$ticket->prioridade]['color'] ?? 'bg-muted text-muted-foreground' }}">{{ $prioridadeLabels[$ticket->prioridade]['label'] ?? $ticket->prioridade }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-foreground">{{ $ticket->titulo }}</h4>
                                        <p class="text-sm text-muted-foreground line-clamp-2">{{ $ticket->descricao }}</p>
                                    </div>
                                    <div class="text-right text-xs text-muted-foreground whitespace-nowrap">
                                        {{ $ticket->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground flex items-center gap-2">
                                    <span>Solicitante: <span class="text-foreground">{{ optional($ticket->solicitante)->name ?? 'N/I' }}</span></span>
                                    @if ($ticket->responsavel)
                                        <span class="text-muted-foreground">•</span>
                                        <span>Responsável: <span class="text-foreground">{{ $ticket->responsavel->name }}</span></span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <p class="text-sm text-muted-foreground">Nenhum ticket encontrado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
