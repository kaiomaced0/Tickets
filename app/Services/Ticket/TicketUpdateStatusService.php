<?php

namespace App\Services\Ticket;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Email\EmailService;

class TicketUpdateStatusService
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    public function handle(Ticket $ticket, string $status, User $user): Ticket
    {
        $statusEnum = TicketStatus::tryFromMixed($status) ?? TicketStatus::ABERTO;

        $fromStatus = $ticket->status;

        $ticket->status = $statusEnum->value;

        if ($statusEnum === TicketStatus::RESOLVIDO && ! $ticket->resolved_at) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $ticket->statusLogs()->create([
            'user_id' => $user->id,
            'from_status' => $fromStatus,
            'to_status' => $ticket->status,
        ]);

        // Dispara notificação via fila quando status muda para RESOLVIDO
        if ($statusEnum === TicketStatus::RESOLVIDO) {
            $this->emailService->notificarTicketResolvido($ticket);
        }

        return $ticket;
    }
}
