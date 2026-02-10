@php
    $statusLabels = [
        'ABERTO' => 'Aberto',
        'EM_ANDAMENTO' => 'Em andamento',
        'RESOLVIDO' => 'Resolvido',
        'CANCELADO' => 'Cancelado',
    ];

    $prioridadeLabels = [
        'BAIXA' => 'Baixa',
        'MEDIA' => 'Média',
        'ALTA' => 'Alta',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Criar Ticket</h2>
            <p class="text-xs text-muted-foreground">Preencha as informações do novo ticket</p>
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

                <form method="POST" action="{{ route('tickets.store-web') }}" class="space-y-6">
                    @csrf

                    <!-- Grid com 2 colunas -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Coluna Esquerda: Título e Descrição -->
                        <div class="lg:col-span-2 rounded-xl border border-border/50 bg-card/60 shadow-sm">
                            <div class="p-6 space-y-6">
                                <div class="border-b border-border/50 pb-4">
                                    <h3 class="text-lg font-semibold text-foreground">Informações do Ticket</h3>
                                    <p class="text-sm text-muted-foreground">Detalhe a solicitação</p>
                                </div>

                                <!-- Título -->
                                <div>
                                    <x-input-label for="titulo" value="Título *" />
                                    <x-text-input
                                        id="titulo"
                                        name="titulo"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('titulo')"
                                        required
                                        autofocus
                                        placeholder="Digite um título descritivo"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('titulo')" />
                                </div>

                                <!-- Descrição -->
                                <div>
                                    <x-input-label for="descricao" value="Descrição *" />
                                    <textarea
                                        id="descricao"
                                        name="descricao"
                                        rows="12"
                                        class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:border-primary focus:ring-primary"
                                        required
                                        placeholder="Descreva detalhadamente o problema ou solicitação"
                                    >{{ old('descricao') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                                </div>
                            </div>
                        </div>

                        <!-- Coluna Direita: Prioridade e Status -->
                        <div class="space-y-6">
                            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                                <div class="p-6 space-y-6">
                                    <div class="border-b border-border/50 pb-4">
                                        <h3 class="text-base font-semibold text-foreground">Classificação</h3>
                                    </div>

                                    <!-- Prioridade -->
                                    <div>
                                        <x-input-label for="prioridade" value="Prioridade *" />
                                        <select
                                            id="prioridade"
                                            name="prioridade"
                                            class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                            required
                                        >
                                            <option value="">Selecione...</option>
                                            @foreach($prioridades as $prioridade)
                                                <option value="{{ $prioridade }}" {{ old('prioridade') === $prioridade ? 'selected' : '' }}>
                                                    {{ $prioridadeLabels[$prioridade] }}
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
                                            <option value="">Selecione...</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ old('status', 'ABERTO') === $status ? 'selected' : '' }}>
                                                    {{ $statusLabels[$status] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Card de Pessoas (apenas para ADMIN) -->
                            @if(Auth::user()->role === 'ADMIN')
                                <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                                    <div class="p-6 space-y-6">
                                        <div class="border-b border-border/50 pb-4">
                                            <h3 class="text-base font-semibold text-foreground">Pessoas</h3>
                                        </div>

                                        <!-- Solicitante -->
                                        <div>
                                            <x-input-label for="solicitante_id" value="Solicitante" />
                                            <select
                                                id="solicitante_id"
                                                name="solicitante_id"
                                                class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                            >
                                                <option value="">Selecione...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('solicitante_id', Auth::id()) == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="mt-1 text-xs text-muted-foreground">
                                                Deixe vazio para usar você como solicitante
                                            </p>
                                            <x-input-error class="mt-2" :messages="$errors->get('solicitante_id')" />
                                        </div>

                                        <!-- Responsável -->
                                        <div>
                                            <x-input-label for="responsavel_id" value="Responsável" />
                                            <select
                                                id="responsavel_id"
                                                name="responsavel_id"
                                                class="mt-1 block w-full rounded-lg border border-border bg-background text-foreground focus:border-primary focus:ring-primary"
                                            >
                                                <option value="">Nenhum responsável</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('responsavel_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                        <div class="px-6 py-4 flex items-center justify-end gap-3">
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
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Criar Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
