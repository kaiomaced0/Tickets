<?php

namespace App\Services\Ticket;

use App\Enums\Prioridade;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketUpdateService
{
    public function handle(Ticket $ticket, array $data): Ticket
    {
        // Detectar mudança de status para criar log
        $statusChanged = false;
        $fromStatus = null;

        if (isset($data['status']) && $data['status'] !== $ticket->status) {
            $statusChanged = true;
            $fromStatus = $ticket->status;
        }

        if (($data['status'] ?? null) === 'RESOLVIDO' && empty($data['resolved_at'])) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        // Criar log de mudança de status
        if ($statusChanged) {
            $ticket->statusLogs()->create([
                'user_id' => Auth::id(),
                'from_status' => $fromStatus,
                'to_status' => $ticket->status,
            ]);
        }

        return $ticket;
    }
}
