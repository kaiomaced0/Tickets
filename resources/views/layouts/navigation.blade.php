<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-border bg-card/50 backdrop-blur supports-[backdrop-filter]:bg-card/50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between min-h-16 gap-4 py-3">
            <div class="flex items-center gap-4 flex-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-foreground font-semibold">
                    <div class="h-10 w-10 rounded-lg bg-primary flex items-center justify-center shadow-sm shadow-primary/30">
                        <x-application-logo class="h-6 w-6 text-primary-foreground" />
                    </div>
                    @if(isset($pageHeader))
                        <div class="hidden sm:flex flex-col leading-tight">
                            {{ $pageHeader }}
                        </div>
                    @else
                        <div class="hidden sm:flex flex-col leading-tight">
                            <span class="text-sm font-semibold text-foreground">Sistema de Tickets</span>
                            <span class="text-xs text-muted-foreground">Painel principal</span>
                        </div>
                    @endif
                </a>

                <div class="hidden md:flex items-center gap-2 text-sm ms-4">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-primary/15 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted/40' }}">Dashboard</a>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-semibold text-foreground">{{ Auth::user()->name }}</p>
                    <div class="flex items-center justify-end gap-2">
                        <span class="text-xs text-muted-foreground">{{ Auth::user()->email }}</span>
                        <span class="px-2 py-0.5 rounded-full border border-border/70 bg-secondary/40 text-[11px] uppercase tracking-wide text-secondary-foreground">{{ Auth::user()->role ?? 'Usuário' }}</span>
                    </div>
                </div>

                <div class="h-10 w-10 rounded-full bg-primary/15 text-primary flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-border/70 text-sm leading-4 font-medium rounded-lg text-foreground bg-card hover:bg-accent/30 focus:outline-none transition ease-in-out duration-150">
                            <div class="hidden sm:block">Menu</div>
                            <div class="sm:ms-2">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('settings.index')">
                            Configurações
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-muted/40 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-border bg-card/80 backdrop-blur">
        @if(isset($pageHeader))
            <div class="px-4 py-3 border-b border-border/60">
                {{ $pageHeader }}
            </div>
        @endif

        <div class="pt-3 pb-2 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-3 pb-4 border-t border-border/70 px-4 space-y-2">
            <div>
                <div class="font-medium text-base text-foreground">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-muted-foreground">{{ Auth::user()->email }}</div>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-2 py-0.5 rounded-full border border-border/70 bg-secondary/40 text-[11px] uppercase tracking-wide text-secondary-foreground">{{ Auth::user()->role ?? 'Usuário' }}</span>
                <div class="h-9 w-9 rounded-full bg-primary/15 text-primary flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('settings.index')">
                    Configurações
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
