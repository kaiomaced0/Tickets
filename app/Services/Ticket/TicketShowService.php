<?php

namespace App\Services\Ticket;

use App\Models\Ticket;

class TicketShowService
{
    public function handle(int $ticketId, bool $withInactive = false): Ticket
    {
        $query = Ticket::query();

        if ($withInactive) {
            $query->withInactive();
        }

        return $query->findOrFail($ticketId);
    }
}
