<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-base text-foreground leading-tight">Perfil</h2>
            <p class="text-xs text-muted-foreground">Gerencie as informações da sua conta</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 rounded-xl border border-border/50 bg-card/60 shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 rounded-xl border border-border/50 bg-card/60 shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 rounded-xl border border-border/50 bg-card/60 shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
