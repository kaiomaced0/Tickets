<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Configurações</h2>
            <p class="text-xs text-muted-foreground">Personalize sua experiência</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Tema -->
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm">
                <div class="p-6 border-b border-border/50">
                    <h3 class="text-lg font-semibold text-foreground">Aparência</h3>
                    <p class="text-sm text-muted-foreground mt-1">Escolha entre tema claro ou escuro</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('settings.theme') }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Light Mode -->
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="theme"
                                    value="light"
                                    {{ ($user->theme ?? 'dark') === 'light' ? 'checked' : '' }}
                                    class="peer sr-only"
                                    onchange="this.form.submit()"
                                />
                                <div class="rounded-lg border-2 border-border/50 bg-card p-6 transition-all peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/20 hover:border-border">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="4"/>
                                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-foreground">Modo Claro</h4>
                                            <p class="text-sm text-muted-foreground">Interface clara e brilhante</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center gap-2">
                                        <div class="h-2 w-full rounded-full bg-blue-200"></div>
                                        <div class="h-2 w-full rounded-full bg-blue-300"></div>
                                        <div class="h-2 w-full rounded-full bg-blue-400"></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Dark Mode -->
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="theme"
                                    value="dark"
                                    {{ ($user->theme ?? 'dark') === 'dark' ? 'checked' : '' }}
                                    class="peer sr-only"
                                    onchange="this.form.submit()"
                                />
                                <div class="rounded-lg border-2 border-border/50 bg-card p-6 transition-all peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/20 hover:border-border">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-foreground">Modo Escuro</h4>
                                            <p class="text-sm text-muted-foreground">Interface escura e confortável</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center gap-2">
                                        <div class="h-2 w-full rounded-full bg-slate-700"></div>
                                        <div class="h-2 w-full rounded-full bg-slate-600"></div>
                                        <div class="h-2 w-full rounded-full bg-primary/50"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @if (session('status') === 'theme-updated')
                            <div
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 3000)"
                                class="p-4 rounded-lg bg-green-700/10 border border-green-700/20 text-sm text-green-700"
                            >
                                Tema atualizado com sucesso!
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Outras configurações futuras -->
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm opacity-50">
                <div class="p-6 border-b border-border/50">
                    <h3 class="text-lg font-semibold text-foreground">Notificações</h3>
                    <p class="text-sm text-muted-foreground mt-1">Gerencie suas preferências de notificação</p>
                </div>
                <div class="p-6">
                    <p class="text-sm text-muted-foreground">Em breve...</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
