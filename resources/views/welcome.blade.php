<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Tickets - Gerenciamento de Solicitações</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b border-border/50 bg-card/60 backdrop-blur-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect width="8" height="8" x="3" y="3" rx="2"/>
                            <path d="M7 11v4a2 2 0 0 0 2 2h4M11 7h4a2 2 0 0 1 2 2v4"/>
                        </svg>
                        <span class="text-xl font-semibold text-foreground">Sistema de Tickets</span>
                    </div>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 transition-colors">
                                    Entrar
                                </a>
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1">
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="max-w-4xl mx-auto text-center space-y-8">
                    <!-- Icon -->
                    <div class="flex justify-center">
                        <div class="rounded-full bg-primary/10 p-6">
                            <svg class="h-16 w-16 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="space-y-4">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground">
                            Sistema de Gerenciamento
                            <span class="text-primary">de Tickets</span>
                        </h1>
                        <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto">
                            Plataforma interna para organizar, acompanhar e resolver solicitações da equipe de forma eficiente e colaborativa.
                        </p>
                    </div>

                    <!-- CTA Button -->
                    @guest
                        <div class="flex flex-col items-center justify-center gap-4 pt-4">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-base font-medium text-primary-foreground bg-primary hover:bg-primary/90 transition-colors shadow-lg shadow-primary/25">
                                Acessar o Sistema
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <p class="text-sm text-muted-foreground">
                                Solicite acesso a um administrador para começar a usar o sistema
                            </p>
                        </div>
                    @endguest
                </div>
            </section>

            <!-- Features Grid -->
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-border/50">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold text-center text-foreground mb-12">
                        Recursos Principais
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Feature 1 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 8v4M12 16h.01"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Abertura de Tickets</h3>
                            <p class="text-sm text-muted-foreground">
                                Crie solicitações de forma rápida e organizada, com descrição detalhada e priorização.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Acompanhamento</h3>
                            <p class="text-sm text-muted-foreground">
                                Monitore o status de cada ticket em tempo real, desde a abertura até a resolução.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-green-500/10 text-green-400 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Gestão Eficiente</h3>
                            <p class="text-sm text-muted-foreground">
                                Dashboard completo com métricas, filtros avançados e relatórios de desempenho.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Controle de Acesso</h3>
                            <p class="text-sm text-muted-foreground">
                                Sistema de permissões para usuários e administradores com diferentes níveis de acesso.
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-pink-500/10 text-pink-400 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Histórico Completo</h3>
                            <p class="text-sm text-muted-foreground">
                                Registro automático de todas as alterações e interações em cada ticket.
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="rounded-xl border border-border/50 bg-card/60 p-6 hover:shadow-lg hover:border-border transition-all">
                            <div class="h-12 w-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.25M15.54 15.54l4.24 4.25M1 12h6M17 12h6M4.22 19.78l4.24-4.25M15.54 8.46l4.24-4.25"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Interface Moderna</h3>
                            <p class="text-sm text-muted-foreground">
                                Design responsivo com modo claro/escuro e experiência otimizada para todos os dispositivos.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-border/50 bg-card/60 py-8">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-muted-foreground">
                    <p>&copy; {{ date('Y') }} Sistema de Tickets. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
