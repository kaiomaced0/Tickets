<?php

namespace App\Services\Ticket;

use App\Enums\Prioridade;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;

class TicketCreateService
{
    public function handle(array $data, User $user): Ticket
    {
        $status = TicketStatus::tryFromMixed($data['status'] ?? null)?->value ?? TicketStatus::ABERTO->value;
        $resolvedAt = $data['resolved_at'] ?? null;

        if ($status === TicketStatus::RESOLVIDO->value && ! $resolvedAt) {
            $resolvedAt = now();
        }

        return Ticket::create([
            'solicitante_id' => $user->id,
            'responsavel_id' => $data['responsavel_id'] ?? null,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
            'status' => $status,
            'prioridade' => Prioridade::tryFromMixed($data['prioridade'] ?? null)?->value ?? Prioridade::MEDIA->value,
            'resolved_at' => $resolvedAt,
        ]);
    }
}
