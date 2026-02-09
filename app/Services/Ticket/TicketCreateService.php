<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketCreateService
{
    public function handle(array $data, User $user): Ticket
    {
        $status = $data['status'] ?? 'ABERTO';
        $resolvedAt = $data['resolved_at'] ?? null;

        if ($status === 'RESOLVIDO' && ! $resolvedAt) {
            $resolvedAt = now();
        }

        return Ticket::create([
            'solicitante_id' => $user->id,
            'responsavel_id' => $data['responsavel_id'] ?? null,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
            'status' => $status,
            'prioridade' => $data['prioridade'] ?? 'MEDIA',
            'resolved_at' => $resolvedAt,
        ]);
    }
}
