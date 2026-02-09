<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketDeleteService
{
    public function handle(Ticket $ticket, User $user): void
    {
        if ($ticket->solicitante_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Somente o solicitante ou um administrador pode excluir o ticket.');
        }

        $ticket->delete();
    }
}
