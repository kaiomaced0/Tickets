<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketStatusLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa tentativa de gerar token com senha incorreta.
     */
    public function test_nao_pode_gerar_token_com_senha_incorreta(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/auth/token', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Credenciais inválidas',
            ]);
    }

    /**
     * Testa tentativa de gerar token para usuário inativo.
     */
    public function test_nao_pode_gerar_token_para_usuario_inativo(): void
    {
        $user = User::factory()->inactive()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/auth/token', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Usuário inativo',
            ]);
    }

    /**
     * Testa que usuário comum (USER) não pode ativar outro usuário.
     */
    public function test_usuario_comum_nao_pode_ativar_outro_usuario(): void
    {
        // Criar dois usuários: um USER e um para ser ativado
        $regularUser = User::factory()->create([
            'role' => 'USER',
            'password' => Hash::make('password'),
        ]);

        $inactiveUser = User::factory()->inactive()->create();

        // Gerar token para o usuário comum
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $regularUser->email,
            'password' => 'password',
        ]);

        $tokenResponse->assertStatus(200);
        $token = $tokenResponse->json('token');
        $this->assertNotEmpty($token);

        // Tentar ativar outro usuário
        $response = $this->postJson("/api/users/{$inactiveUser->id}/toggle-active", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(403);
    }

    /**
     * Testa que solicitante pode inativar seu próprio ticket.
     */
    public function test_solicitante_pode_inativar_proprio_ticket(): void
    {
        // Criar usuário solicitante
        $solicitante = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Criar ticket com o solicitante
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'active' => true,
        ]);

        // Gerar token
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $solicitante->email,
            'password' => 'password',
        ]);

        $token = $tokenResponse->json('token');

        // Inativar o ticket
        $response = $this->postJson("/api/tickets/{$ticket->id}/toggle-active", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Verificar que o ticket foi inativado
        $ticket->refresh();
        $this->assertNotNull($ticket->deleted_at);
    }

    /**
     * Testa que usuário não pode inativar ticket sem ligação.
     */
    public function test_usuario_nao_pode_inativar_ticket_sem_ligacao(): void
    {
        // Criar usuário sem ligação com o ticket
        $unrelatedUser = User::factory()->create([
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);

        // Criar outro usuário como solicitante
        $solicitante = User::factory()->create();

        // Criar ticket sem ligação com o primeiro usuário
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'responsavel_id' => null,
            'active' => true,
        ]);

        // Gerar token para o usuário sem ligação
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $unrelatedUser->email,
            'password' => 'password',
        ]);

        $token = $tokenResponse->json('token');

        // Tentar inativar o ticket
        $response = $this->postJson("/api/tickets/{$ticket->id}/toggle-active", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(403);

        // Verificar que o ticket permanece ativo
        $ticket->refresh();
        $this->assertNull($ticket->deleted_at);
    }

    /**
     * Testa que admin pode inativar qualquer ticket.
     */
    public function test_admin_pode_inativar_qualquer_ticket(): void
    {
        // Criar admin
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password'),
        ]);

        // Criar outro usuário como solicitante
        $solicitante = User::factory()->create();

        // Criar ticket
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'active' => true,
        ]);

        // Gerar token para admin
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $token = $tokenResponse->json('token');

        // Inativar o ticket
        $response = $this->postJson("/api/tickets/{$ticket->id}/toggle-active", [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Verificar que o ticket foi inativado
        $ticket->refresh();
        $this->assertNotNull($ticket->deleted_at);
    }

    /**
     * Testa que PATCH de atualizar ticket cria registro de log e atualiza resolved_at.
     */
    public function test_patch_ticket_cria_log_de_status_e_atualiza_resolucao(): void
    {
        // Criar usuário solicitante
        $solicitante = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Criar ticket ABERTO
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'status' => 'ABERTO',
            'resolved_at' => null,
        ]);

        // Gerar token
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $solicitante->email,
            'password' => 'password',
        ]);

        $token = $tokenResponse->json('token');

        // Atualizar ticket para RESOLVIDO
        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'status' => 'RESOLVIDO',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);

        // Verificar que o ticket foi atualizado para RESOLVIDO
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'RESOLVIDO',
        ]);

        // Verificar que resolved_at foi populado
        $ticket->refresh();
        $this->assertNotNull($ticket->resolved_at);

        // Verificar que foi criado um log de status
        $this->assertDatabaseHas('ticket_status_logs', [
            'ticket_id' => $ticket->id,
            'user_id' => $solicitante->id,
            'from_status' => 'ABERTO',
            'to_status' => 'RESOLVIDO',
        ]);
    }

    /**
     * Testa múltiplas mudanças de status criam múltiplos logs.
     */
    public function test_multiplas_mudancas_de_status_criam_multiplos_logs(): void
    {
        // Criar usuário
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Criar ticket
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $user->id,
            'status' => 'ABERTO',
        ]);

        // Gerar token
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $tokenResponse->json('token');

        // Primeira mudança: ABERTO -> EM_ANDAMENTO
        $this->patchJson("/api/tickets/{$ticket->id}", [
            'status' => 'EM_ANDAMENTO',
        ], [
            'Authorization' => "Bearer {$token}",
        ])->assertStatus(200);

        // Segunda mudança: EM_ANDAMENTO -> RESOLVIDO
        $this->patchJson("/api/tickets/{$ticket->id}", [
            'status' => 'RESOLVIDO',
        ], [
            'Authorization' => "Bearer {$token}",
        ])->assertStatus(200);

        // Verificar que existem 2 logs
        $logs = TicketStatusLog::where('ticket_id', $ticket->id)->get();
        $this->assertCount(2, $logs);

        // Verificar primeiro log
        $this->assertEquals('ABERTO', $logs[0]->from_status);
        $this->assertEquals('EM_ANDAMENTO', $logs[0]->to_status);

        // Verificar segundo log
        $this->assertEquals('EM_ANDAMENTO', $logs[1]->from_status);
        $this->assertEquals('RESOLVIDO', $logs[1]->to_status);
    }

    /**
     * Usuário não autenticado NÃO pode acessar lista de tickets via API.
     */
    public function test_usuario_nao_autenticado_nao_pode_acessar_tickets_api(): void
    {
        $response = $this->getJson('/api/tickets');

        $response->assertStatus(401);
    }

    /**
     * Usuário não autenticado NÃO pode criar ticket via API.
     */
    public function test_usuario_nao_autenticado_nao_pode_criar_ticket_api(): void
    {
        $response = $this->postJson('/api/tickets', [
            'titulo' => 'Titulo de teste para validar',
            'descricao' => 'Descricao com pelo menos vinte caracteres para validar',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Usuário não autenticado NÃO pode ver ticket via API.
     */
    public function test_usuario_nao_autenticado_nao_pode_ver_ticket_api(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['solicitante_id' => $user->id]);

        $response = $this->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(401);
    }

    /**
     * Usuário não autenticado NÃO pode alterar status via API.
     */
    public function test_usuario_nao_autenticado_nao_pode_alterar_status_api(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['solicitante_id' => $user->id]);

        $response = $this->patchJson("/api/tickets/{$ticket->id}/status", [
            'status' => 'EM_ANDAMENTO',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Solicitante pode atualizar seu próprio ticket.
     */
    public function test_solicitante_pode_atualizar_proprio_ticket(): void
    {
        $solicitante = User::factory()->create(['password' => Hash::make('password')]);
        $ticket = Ticket::factory()->create(['solicitante_id' => $solicitante->id]);

        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $solicitante->email,
            'password' => 'password',
        ]);
        $token = $tokenResponse->json('token');

        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'titulo' => 'Titulo atualizado pelo solicitante',
        ], ['Authorization' => "Bearer {$token}"]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'titulo' => 'Titulo atualizado pelo solicitante',
        ]);
    }

    /**
     * Responsável pode atualizar ticket que é responsável.
     */
    public function test_responsavel_pode_atualizar_ticket(): void
    {
        $solicitante = User::factory()->create();
        $responsavel = User::factory()->create(['password' => Hash::make('password')]);
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'responsavel_id' => $responsavel->id,
        ]);

        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $responsavel->email,
            'password' => 'password',
        ]);
        $token = $tokenResponse->json('token');

        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'titulo' => 'Titulo atualizado pelo responsavel',
        ], ['Authorization' => "Bearer {$token}"]);

        $response->assertStatus(200);
    }

    /**
     * Admin pode atualizar qualquer ticket.
     */
    public function test_admin_pode_atualizar_qualquer_ticket(): void
    {
        $admin = User::factory()->admin()->create(['password' => Hash::make('password')]);
        $solicitante = User::factory()->create();
        $ticket = Ticket::factory()->create(['solicitante_id' => $solicitante->id]);

        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $admin->email,
            'password' => 'password',
        ]);
        $token = $tokenResponse->json('token');

        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'titulo' => 'Titulo atualizado pelo admin',
        ], ['Authorization' => "Bearer {$token}"]);

        $response->assertStatus(200);
    }

    /**
     * Usuário sem relação NÃO pode atualizar ticket de outro.
     */
    public function test_usuario_sem_relacao_nao_pode_atualizar_ticket(): void
    {
        $solicitante = User::factory()->create();
        $outroUser = User::factory()->create(['password' => Hash::make('password'), 'role' => 'USER']);
        $ticket = Ticket::factory()->create([
            'solicitante_id' => $solicitante->id,
            'responsavel_id' => null,
        ]);

        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $outroUser->email,
            'password' => 'password',
        ]);
        $token = $tokenResponse->json('token');

        $response = $this->patchJson("/api/tickets/{$ticket->id}", [
            'titulo' => 'Tentativa de atualizar',
        ], ['Authorization' => "Bearer {$token}"]);

        $response->assertStatus(403);
    }
}
