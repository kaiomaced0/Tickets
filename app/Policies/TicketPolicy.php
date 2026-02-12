<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Qualquer usuário autenticado pode listar tickets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Qualquer usuário autenticado pode visualizar um ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return true;
    }

    /**
     * Qualquer usuário autenticado pode criar tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Somente o solicitante, responsável ou admin pode atualizar o ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->solicitante_id === $user->id
            || $ticket->responsavel_id === $user->id;
    }

    /**
     * Somente o solicitante ou admin pode excluir (soft delete) o ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->solicitante_id === $user->id;
    }

    /**
     * Somente o solicitante ou admin pode ativar/inativar o ticket.
     */
    public function toggleActive(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin()
            || $ticket->solicitante_id === $user->id;
    }

    /**
     * Qualquer usuário autenticado pode atualizar o status.
     */
    public function updateStatus(User $user, Ticket $ticket): bool
    {
        return true;
    }
}
