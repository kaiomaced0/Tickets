<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Negado - Sistema de Tickets</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center space-y-8">
            <!-- Icon -->
            <div class="flex justify-center">
                <svg class="h-24 w-24 text-destructive" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h1 class="text-4xl font-bold text-foreground">Acesso Negado</h1>
                <p class="text-lg text-muted-foreground">
                    {{ $exception->getMessage() ?: 'Você não tem permissão para acessar este recurso.' }}
                </p>
            </div>

            <!-- Action Button -->
            <div class="pt-4">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-medium text-primary-foreground bg-primary hover:bg-primary/90 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Voltar para Início
                </a>
            </div>

            <!-- Secondary Info -->
            <div class="pt-8 text-sm text-muted-foreground">
                <p>Erro 403 - Acesso Negado</p>
            </div>
        </div>
    </div>
</body>
</html>
