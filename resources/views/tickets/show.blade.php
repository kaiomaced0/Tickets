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

    $isAdmin = Auth::user()->role === 'ADMIN';
    $isSolicitante = Auth::user()->id === $ticket->solicitante_id;
    $isResponsavel = Auth::user()->id === $ticket->responsavel_id;

    // Permissões granulares
    $canEditBasic = $isAdmin || $isSolicitante; // Título e descrição
    $canEditClassification = $isAdmin || $isSolicitante || $isResponsavel; // Prioridade e status
    $canEditUsers = $isAdmin; // Solicitante e responsável
    // USER só pode se auto-atribuir se ainda não houver responsável
    $canSelfAssign = Auth::user()->role === 'USER' && !$isResponsavel && empty($ticket->responsavel_id);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Ticket #{{ $ticket->id }}</h2>
            <p class="text-xs text-muted-foreground">Detalhes e edição do ticket</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto space-y-4">
                <!-- Botão Voltar -->
                <div>
                    <a href="{{ route('tickets.list') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-border bg-secondary text-foreground text-sm font-medium hover:bg-secondary/80 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Voltar
                    </a>
                </div>

                @if(session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 dark:border-green-900 dark:bg-green-950/20 p-4">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                            <p class="text-sm text-green-600 dark:text-green-400 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('tickets.update-web', $ticket->id) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    @csrf
                    @method('PATCH')

                    <!-- Coluna Principal - Informações do Ticket -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Card de Título e Descrição -->
                        <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm p-6 space-y-6">
                            <!-- Cabeçalho com Status e Prioridade -->
                            <div class="flex items-center justify-between border-b border-border/50 pb-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusLabels[$ticket->status]['color'] }}">
                                        {{ $statusLabels[$ticket->status]['label'] }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeLabels[$ticket->prioridade]['color'] }}">
                                        {{ $prioridadeLabels[$ticket->prioridade]['label'] }}
                                    </span>
                                </div>
                                <div class="text-right text-xs text-muted-foreground">
                                    <p>Criado em {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                    @if($ticket->updated_at != $ticket->created_at)
                                        <p>Atualizado em {{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Título -->
                            <div>
                                <x-input-label for="titulo" value="Título *" />
                                @if($canEditBasic)
                                    <x-text-input
                                        id="titulo"
                                        name="titulo"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('titulo', $ticket->titulo)"
                                        required
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('titulo')" />
                                @else
                                    <p class="mt-1 text-foreground">{{ $ticket->titulo }}</p>
                                @endif
                            </div>

                            <!-- Descrição -->
                            <div>
                                <x-input-label for="descricao" value="Descrição *" />
                                @if($canEditBasic)
                                    <textarea
                                        id="descricao"
                                        name="descricao"
                                        rows="12"
                                        class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-primary"
                                        required
                                    >{{ old('descricao', $ticket->descricao) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                                @else
                                    <p class="mt-1 text-foreground whitespace-pre-line">{{ $ticket->descricao }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Card de Pessoas Envolvidas -->
                        <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-foreground border-b border-border/50 pb-3">Pessoas Envolvidas</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Solicitante -->
                                <div>
                                    <x-input-label for="solicitante_id" value="Solicitante" />
                                    @if($canEditUsers)
                                        <select
                                            id="solicitante_id"
                                            name="solicitante_id"
                                            class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                        >
                                            <option value="">Selecione o solicitante</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('solicitante_id', $ticket->solicitante_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('solicitante_id')" />
                                    @else
                                        <div class="mt-2 flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-medium">
                                                {{ strtoupper(substr($ticket->solicitante->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-foreground">{{ $ticket->solicitante->name }}</p>
                                                <p class="text-xs text-muted-foreground">{{ $ticket->solicitante->email }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Responsável -->
                                <div>
                                    <x-input-label for="responsavel_id" value="Responsável" />
                                    @if($canEditUsers)
                                        <select
                                            id="responsavel_id"
                                            name="responsavel_id"
                                            class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                        >
                                            <option value="">Nenhum responsável</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('responsavel_id', $ticket->responsavel_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                                    @elseif($canSelfAssign)
                                        <select
                                            id="responsavel_id"
                                            name="responsavel_id"
                                            class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                        >
                                            <option value="">Nenhum responsável</option>
                                            <option value="{{ Auth::id() }}">
                                                {{ Auth::user()->name }} (Me atribuir)
                                            </option>
                                        </select>
                                        <p class="mt-1 text-xs text-muted-foreground">Você pode se atribuir como responsável deste ticket</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                                    @else
                                        @if($ticket->responsavel)
                                            <div class="mt-2 flex items-center gap-3 p-3 bg-secondary/30 rounded-lg border border-border/50">
                                                <div class="h-10 w-10 rounded-full bg-primary/20 text-primary flex items-center justify-center text-sm font-medium">
                                                    {{ strtoupper(substr($ticket->responsavel->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-foreground">{{ $ticket->responsavel->name }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $ticket->responsavel->email }}</p>
                                                </div>
                                            </div>
                                            @if(Auth::user()->role === 'USER' && !$isResponsavel)
                                                <p class="mt-2 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                                    </svg>
                                                    Este ticket já possui um responsável. Apenas admins podem alterar.
                                                </p>
                                            @endif
                                        @else
                                            <p class="mt-2 text-sm text-muted-foreground italic">Nenhum responsável atribuído</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna Lateral - Classificação -->
                    <div class="space-y-4">
                        <!-- Card de Classificação -->
                        <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-foreground border-b border-border/50 pb-3">Classificação</h3>

                            <!-- Prioridade -->
                            <div>
                                <x-input-label for="prioridade" value="Prioridade *" />
                                @if($canEditClassification)
                                    <select
                                        id="prioridade"
                                        name="prioridade"
                                        class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                        required
                                    >
                                        @foreach(['BAIXA', 'MEDIA', 'ALTA'] as $prioridade)
                                            <option value="{{ $prioridade }}" {{ old('prioridade', $ticket->prioridade) === $prioridade ? 'selected' : '' }}>
                                                {{ $prioridadeLabels[$prioridade]['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('prioridade')" />
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeLabels[$ticket->prioridade]['color'] }} mt-2">
                                        {{ $prioridadeLabels[$ticket->prioridade]['label'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" value="Status *" />
                                @if($canEditClassification)
                                    <select
                                        id="status"
                                        name="status"
                                        class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                        required
                                    >
                                        @foreach(['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $ticket->status) === $status ? 'selected' : '' }}>
                                                {{ $statusLabels[$status]['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusLabels[$ticket->status]['color'] }} mt-2">
                                        {{ $statusLabels[$ticket->status]['label'] }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        @if($canEditBasic || $canEditClassification || $canEditUsers || $canSelfAssign)
                            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm p-6">
                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors"
                                >
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>
                                    Salvar Alterações
                                </button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
