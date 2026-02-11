<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-foreground">
            Desativar Conta
        </h2>

        <p class="mt-1 text-sm text-muted-foreground">
            Ao desativar sua conta, você não poderá mais acessar o sistema. Um administrador poderá reativá-la posteriormente se necessário.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Desativar Conta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('patch')

            <h2 class="text-lg font-medium text-foreground">
                Tem certeza de que deseja desativar sua conta?
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Ao desativar sua conta, você será desconectado e não poderá fazer login até que um administrador reative sua conta. Por favor, digite sua senha para confirmar.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Senha" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Senha"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Desativar Conta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
