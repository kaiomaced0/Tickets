<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Usuário não autenticado NÃO pode acessar lista de tickets via web.
     */
    public function test_usuario_nao_autenticado_nao_pode_acessar_tickets_web(): void
    {
        $response = $this->get('/tickets');

        $response->assertRedirect('/login');
    }

    /**
     * SoftDelete mantém o ticket no banco com deleted_at preenchido.
     */
    public function test_soft_delete_preenche_deleted_at(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['solicitante_id' => $user->id]);

        $ticketId = $ticket->id;
        $ticket->delete();

        // Não deve encontrar com query normal (SoftDeletes filtra)
        $this->assertNull(Ticket::find($ticketId));

        // Deve encontrar com withTrashed
        $trashed = Ticket::withTrashed()->find($ticketId);
        $this->assertNotNull($trashed);
        $this->assertNotNull($trashed->deleted_at);
    }

    /**
     * Ticket soft-deleted pode ser restaurado.
     */
    public function test_ticket_soft_deleted_pode_ser_restaurado(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['solicitante_id' => $user->id]);

        $ticketId = $ticket->id;
        $ticket->delete();

        $trashed = Ticket::withTrashed()->find($ticketId);
        $trashed->restore();

        $restored = Ticket::find($ticketId);
        $this->assertNotNull($restored);
        $this->assertNull($restored->deleted_at);
    }
}
