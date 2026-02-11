<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Gerenciar Usuários</h2>
            <p class="text-xs text-muted-foreground">Lista de todos os usuários do sistema</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 p-4">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 p-4">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filtros e Ações -->
            <div class="mb-6 rounded-xl border border-border/50 bg-card/60 shadow-sm p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-end">
                    <!-- Busca -->
                    <div class="flex-1 min-w-0">
                        <label class="block text-xs font-medium text-muted-foreground mb-1.5">Buscar</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nome ou e-mail..."
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                    </div>

                    <!-- Filtro de Role -->
                    <div class="w-full lg:w-36">
                        <label class="block text-xs font-medium text-muted-foreground mb-1.5">Tipo</label>
                        <select
                            name="role"
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                            <option value="">Todos</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="USER" {{ request('role') === 'USER' ? 'selected' : '' }}>Usuário</option>
                        </select>
                    </div>

                    <!-- Filtro de Status -->
                    <div class="w-full lg:w-36">
                        <label class="block text-xs font-medium text-muted-foreground mb-1.5">Status</label>
                        <select
                            name="active"
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                            <option value="">Todos</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary/15 border border-primary/30 rounded-lg font-medium text-sm text-primary hover:bg-primary/25 focus:outline-none focus:ring-2 focus:ring-primary/50 transition whitespace-nowrap">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filtrar
                        </button>

                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary border border-primary/30 rounded-lg font-medium text-sm text-primary-foreground hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary transition shadow-sm whitespace-nowrap">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Novo Usuário
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabela de Usuários -->
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted/30 border-b border-border/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">E-mail</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @forelse ($users as $user)
                                <tr class="hover:bg-muted/20 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-primary/15 text-primary flex items-center justify-center text-sm font-semibold">
                                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-foreground">{{ $user->name }}</div>
                                                @if ($user->id === auth()->id())
                                                    <div class="text-xs text-muted-foreground">(Você)</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-foreground">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $user->role === 'ADMIN' ? 'bg-purple-500/15 text-purple-400 border border-purple-500/30' : 'bg-blue-500/15 text-blue-400 border border-blue-500/30' }}">
                                            {{ $user->role === 'ADMIN' ? 'Admin' : 'Usuário' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            onclick="toggleUserActive({{ $user->id }}, {{ $user->active ? 'true' : 'false' }})"
                                            class="px-2.5 py-1 rounded-full text-xs font-medium {{ $user->active ? 'bg-green-500/15 text-green-400 border border-green-500/30' : 'bg-red-500/15 text-red-400 border border-red-500/30' }} hover:opacity-80 transition"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                        >
                                            {{ $user->active ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-primary/15 border border-primary/30 rounded-lg text-xs font-medium text-primary hover:bg-primary/25 transition">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="text-sm text-muted-foreground">Nenhum usuário encontrado</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginação -->
            @if ($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleUserActive(userId, currentActive) {
            fetch(`/admin/users/${userId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao alterar status do usuário');
            });
        }
    </script>
</x-app-layout>
