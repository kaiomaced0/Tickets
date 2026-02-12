<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TicketToggleActiveService
{
    public function handle(Ticket $ticket, User $user): Ticket
    {
        Gate::forUser($user)->authorize('toggleActive', $ticket);

        $ticket->active = !$ticket->active;
        $ticket->save();

        return $ticket;
    }
}
