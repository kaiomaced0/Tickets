<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Painel Administrativo</h2>
            <p class="text-xs text-muted-foreground">Gerenciamento e configurações do sistema</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card Tickets -->
                <a href="{{ route('tickets.list') }}" class="group">
                    <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm hover:shadow-md hover:border-primary/50 transition-all h-full">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="h-12 w-12 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect width="8" height="8" x="3" y="3" rx="2"/>
                                        <path d="M7 11v4a2 2 0 0 0 2 2h4M11 7h4a2 2 0 0 1 2 2v4"/>
                                    </svg>
                                </div>
                                <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-1">Tickets</h3>
                            <p class="text-sm text-muted-foreground">
                                Gerenciar tickets do sistema, visualizar status e atribuir responsáveis
                            </p>
                        </div>
                    </div>
                </a>

                <!-- Card Usuários -->
                <a href="{{ route('admin.users.index') }}" class="group">
                    <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm hover:shadow-md hover:border-primary/50 transition-all h-full">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="h-12 w-12 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-1">Usuários</h3>
                            <p class="text-sm text-muted-foreground">
                                Gerenciar usuários do sistema, criar contas e definir permissões
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
