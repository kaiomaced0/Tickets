<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Editar Usuário</h2>
            <p class="text-xs text-muted-foreground">Atualize as informações do usuário</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-2xl">
            <div class="rounded-xl border border-border/50 bg-card/60 shadow-sm p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-foreground mb-1.5">
                            Nome <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 @error('name') border-red-500/50 @enderror"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- E-mail -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-foreground mb-1.5">
                            E-mail <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 @error('email') border-red-500/50 @enderror"
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-foreground mb-1.5">
                            Nova Senha <span class="text-xs text-muted-foreground">(deixe em branco para não alterar)</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 @error('password') border-red-500/50 @enderror"
                        >
                        @error('password')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-1.5">
                            Confirmar Nova Senha
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                        >
                    </div>

                    <!-- Tipo de Usuário -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-foreground mb-1.5">
                            Tipo de Usuário <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full rounded-lg border border-border/70 bg-background/50 px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 @error('role') border-red-500/50 @enderror"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}
                        >
                            <option value="USER" {{ old('role', $user->role) === 'USER' ? 'selected' : '' }}>Usuário</option>
                            <option value="ADMIN" {{ old('role', $user->role) === 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @if ($user->id === auth()->id())
                            <p class="mt-1 text-xs text-muted-foreground">Você não pode alterar seu próprio tipo de usuário</p>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                        @error('role')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="active"
                            name="active"
                            value="1"
                            {{ old('active', $user->active) ? 'checked' : '' }}
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}
                            class="h-4 w-4 rounded border-border/70 bg-background/50 text-primary focus:ring-2 focus:ring-primary/50 focus:ring-offset-0"
                        >
                        <label for="active" class="ml-2 text-sm text-foreground">
                            Usuário ativo
                        </label>
                        @if ($user->id === auth()->id())
                            <span class="ml-2 text-xs text-muted-foreground">(Você não pode desativar sua própria conta)</span>
                            <input type="hidden" name="active" value="1">
                        @endif
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center gap-3 pt-4 border-t border-border/50">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-primary/30 rounded-lg font-medium text-sm text-primary-foreground hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary transition shadow-sm"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Salvar Alterações
                        </button>

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-muted/30 border border-border/70 rounded-lg font-medium text-sm text-foreground hover:bg-muted/50 focus:outline-none focus:ring-2 focus:ring-muted transition"
                        >
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
