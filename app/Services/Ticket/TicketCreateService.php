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

        // Se solicitante_id não foi informado, usa o usuário logado
        // Isso permite que admins atribuam tickets para outros usuários
        $solicitanteId = $data['solicitante_id'] ?? $user->id;

        return Ticket::create([
            'solicitante_id' => $solicitanteId,
            'responsavel_id' => $data['responsavel_id'] ?? null,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
            'status' => $status,
            'prioridade' => Prioridade::tryFromMixed($data['prioridade'] ?? null)?->value ?? Prioridade::MEDIA->value,
            'resolved_at' => $resolvedAt,
        ]);
    }
}
