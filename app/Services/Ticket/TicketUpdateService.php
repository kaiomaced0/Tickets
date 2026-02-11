<?php

namespace App\Services\Ticket;

use App\Enums\Prioridade;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Services\Email\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TicketUpdateService
{
    public function __construct(
        protected EmailService $emailService,
    ) {}

    public function handle(Ticket $ticket, array $data): Ticket
    {
        // Validar auto-atribuição de USER quando já existe responsável
        if (isset($data['responsavel_id'])) {
            $user = Auth::user();
            $isUser = $user->role === 'USER';
            $isTryingSelfAssign = $data['responsavel_id'] == $user->id;
            $alreadyHasResponsavel = !empty($ticket->responsavel_id) && $ticket->responsavel_id != $user->id;

            if ($isUser && $isTryingSelfAssign && $alreadyHasResponsavel) {
                throw ValidationException::withMessages([
                    'responsavel_id' => 'Este ticket já possui um responsável atribuído. Apenas administradores podem alterar.'
                ]);
            }
        }

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

            // Dispara notificação via fila quando status muda para RESOLVIDO
            if ($ticket->status === TicketStatus::RESOLVIDO->value) {
                $this->emailService->notificarTicketResolvido($ticket);
            }
        }

        return $ticket;
    }
}
