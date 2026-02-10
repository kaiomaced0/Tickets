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

    $canEdit = Auth::user()->role === 'ADMIN' ||
               Auth::user()->id === $ticket->solicitante_id ||
               Auth::user()->id === $ticket->responsavel_id;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-base text-foreground leading-tight">Ticket #{{ $ticket->id }}</h2>
                <p class="text-xs text-muted-foreground">Detalhes e edição do ticket</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tickets.list') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-border bg-secondary text-foreground text-sm font-medium hover:bg-secondary/80 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto space-y-6">
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

                <!-- Informações do Ticket -->
                <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                    <div class="p-6 border-b border-border/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-foreground">Informações do Ticket</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusLabels[$ticket->status]['color'] }}">
                                    {{ $statusLabels[$ticket->status]['label'] }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeLabels[$ticket->prioridade]['color'] }}">
                                    {{ $prioridadeLabels[$ticket->prioridade]['label'] }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-muted-foreground">
                            <p>Criado em {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            @if($ticket->updated_at != $ticket->created_at)
                                <p>Atualizado em {{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($canEdit)
                        <form method="POST" action="{{ route('tickets.update-web', $ticket->id) }}" class="p-6 space-y-6">
                            @csrf
                            @method('PATCH')

                            <!-- Título -->
                            <div>
                                <x-input-label for="titulo" value="Título *" />
                                <x-text-input
                                    id="titulo"
                                    name="titulo"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('titulo', $ticket->titulo)"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('titulo')" />
                            </div>

                            <!-- Descrição -->
                            <div>
                                <x-input-label for="descricao" value="Descrição *" />
                                <textarea
                                    id="descricao"
                                    name="descricao"
                                    rows="6"
                                    class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-primary"
                                    required
                                >{{ old('descricao', $ticket->descricao) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                            </div>

                            <!-- Prioridade e Status -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Prioridade -->
                                <div>
                                    <x-input-label for="prioridade" value="Prioridade *" />
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
                                </div>

                                <!-- Status -->
                                <div>
                                    <x-input-label for="status" value="Status *" />
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
                                </div>
                            </div>

                            <!-- Responsável -->
                            @if(Auth::user()->role === 'ADMIN')
                                <div>
                                    <x-input-label for="responsavel_id" value="Responsável" />
                                    <select
                                        id="responsavel_id"
                                        name="responsavel_id"
                                        class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                    >
                                        <option value="">Nenhum responsável</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('responsavel_id', $ticket->responsavel_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                                </div>
                            @endif

                            <div class="pt-4 border-t border-border/50 flex items-center justify-end gap-3">
                                <a
                                    href="{{ route('tickets.list') }}"
                                    class="inline-flex items-center px-4 py-2 rounded-lg border border-border bg-secondary text-foreground text-sm font-medium hover:bg-secondary/80 transition-colors"
                                >
                                    Cancelar
                                </a>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-5 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors"
                                >
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="p-6 space-y-6">
                            <!-- Título -->
                            <div>
                                <x-input-label value="Título" />
                                <p class="mt-1 text-foreground">{{ $ticket->titulo }}</p>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <x-input-label value="Descrição" />
                                <p class="mt-1 text-foreground whitespace-pre-line">{{ $ticket->descricao }}</p>
                            </div>

                            <!-- Prioridade e Status -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label value="Prioridade" />
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeLabels[$ticket->prioridade]['color'] }} mt-2">
                                        {{ $prioridadeLabels[$ticket->prioridade]['label'] }}
                                    </span>
                                </div>

                                <div>
                                    <x-input-label value="Status" />
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusLabels[$ticket->status]['color'] }} mt-2">
                                        {{ $statusLabels[$ticket->status]['label'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Informações de Usuários -->
                <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                    <div class="p-6 border-b border-border/50">
                        <h3 class="text-lg font-semibold text-foreground">Pessoas Envolvidas</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Solicitante -->
                        <div>
                            <x-input-label value="Solicitante" />
                            <div class="mt-2 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr($ticket->solicitante->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-foreground">{{ $ticket->solicitante->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $ticket->solicitante->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Responsável -->
                        <div>
                            <x-input-label value="Responsável" />
                            @if($ticket->responsavel)
                                <div class="mt-2 flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-secondary/50 text-foreground flex items-center justify-center text-sm font-medium">
                                        {{ strtoupper(substr($ticket->responsavel->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-foreground">{{ $ticket->responsavel->name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $ticket->responsavel->email }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="mt-2 text-sm text-muted-foreground italic">Nenhum responsável atribuído</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
