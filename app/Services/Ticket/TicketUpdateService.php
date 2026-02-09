<?php

namespace App\Services\Ticket;

use App\Models\Ticket;

class TicketUpdateService
{
    public function handle(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);

        return $ticket;
    }
}
