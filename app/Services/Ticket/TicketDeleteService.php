<?php

namespace App\Services\Ticket;

use App\Models\Ticket;

class TicketDeleteService
{
    public function handle(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
