<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tela_de_registro_pode_ser_renderizada(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_novos_usuarios_podem_se_registrar(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Novos usuários não são autenticados automaticamente
        // Eles precisam de aprovação de um administrador
        $this->assertGuest();
        $response->assertRedirect(route('login', absolute: false));

        // Verificar que o usuário foi criado como inativo
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'active' => false,
        ]);
    }
}
