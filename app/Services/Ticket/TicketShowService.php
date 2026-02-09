<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketShowService
{
    public function handle(int $ticketId, User $user): Ticket
    {
        return Ticket::query()
            ->whereBelongsTo($user)
            ->findOrFail($ticketId);
    }
}
