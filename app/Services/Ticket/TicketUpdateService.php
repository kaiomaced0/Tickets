<?php

namespace App\Services\Ticket;

use App\Models\Ticket;

class TicketUpdateService
{
    public function handle(Ticket $ticket, array $data): Ticket
    {
        if (($data['status'] ?? null) === 'RESOLVIDO' && empty($data['resolved_at'])) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        return $ticket;
    }
}
