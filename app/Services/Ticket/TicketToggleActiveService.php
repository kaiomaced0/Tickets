<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketToggleActiveService
{
    public function handle(Ticket $ticket, User $user): Ticket
    {
        if ($ticket->solicitante_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Somente o solicitante ou um administrador pode inativar/ativar o ticket.');
        }

        $ticket->active = !$ticket->active;
        $ticket->save();

        return $ticket;
    }
}
