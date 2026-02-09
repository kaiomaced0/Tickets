<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketUpdateStatusService
{
    public function handle(Ticket $ticket, string $status, User $user): Ticket
    {
        $fromStatus = $ticket->status;

        $ticket->status = $status;

        if ($status === 'RESOLVIDO' && ! $ticket->resolved_at) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $ticket->statusLogs()->create([
            'user_id' => $user->id,
            'from_status' => $fromStatus,
            'to_status' => $status,
        ]);

        return $ticket;
    }
}
