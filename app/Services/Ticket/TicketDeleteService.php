<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TicketDeleteService
{
    public function handle(Ticket $ticket, User $user): void
    {
        Gate::forUser($user)->authorize('delete', $ticket);

        $ticket->delete();
    }
}
