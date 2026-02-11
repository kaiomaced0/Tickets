<?php

namespace App\Services\Email;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketResolvidoNotification;

class EmailService
{
    /**
     * Envia notificação de ticket resolvido para o solicitante via fila.
     */
    public function notificarTicketResolvido(Ticket $ticket): void
    {
        $solicitante = $ticket->solicitante;

        if (! $solicitante) {
            return;
        }

        $solicitante->notify(new TicketResolvidoNotification($ticket));
    }
}
