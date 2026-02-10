<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketListService
{
    public function __construct(private readonly int $defaultPerPage = 10)
    {
    }

    public function handle(?User $user = null, array $filters = [], ?int $perPage = null): LengthAwarePaginator
    {
        $query = Ticket::query()->latest();

        // Lógica de permissão para tickets cancelados
        if ($user && $user->role !== 'ADMIN') {
            // Se está buscando especificamente por CANCELADO ou não há filtro de status
            // Usuários comuns só veem cancelados onde estão envolvidos
            $query->where(function ($q) use ($user, $filters) {
                // Tickets não cancelados: sem restrição
                $q->where('status', '!=', 'CANCELADO');

                // OU tickets cancelados onde o usuário está envolvido
                $q->orWhere(function ($subQuery) use ($user) {
                    $subQuery->where('status', 'CANCELADO')
                        ->where(function ($involvementQuery) use ($user) {
                            $involvementQuery->where('solicitante_id', $user->id)
                                ->orWhere('responsavel_id', $user->id);
                        });
                });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['prioridade'])) {
            $query->where('prioridade', $filters['prioridade']);
        }

        if (! empty($filters['solicitante_id'])) {
            $query->where('solicitante_id', $filters['solicitante_id']);
        }

        if (! empty($filters['responsavel_id'])) {
            $query->where('responsavel_id', $filters['responsavel_id']);
        }

        if (array_key_exists('active', $filters)) {
            $filters['active']
                ? $query->withoutGlobalScope('active')->where('active', true)
                : $query->withoutGlobalScope('active')->where('active', false);
        }

        if (! empty($filters['q'])) {
            $query->where(function ($q) use ($filters): void {
                $q->where('titulo', 'like', '%'.$filters['q'].'%')
                    ->orWhere('descricao', 'like', '%'.$filters['q'].'%')
                    ->orWhereHas('solicitante', function ($userQuery) use ($filters): void {
                        $userQuery->where('name', 'like', '%'.$filters['q'].'%');
                    })
                    ->orWhereHas('responsavel', function ($userQuery) use ($filters): void {
                        $userQuery->where('name', 'like', '%'.$filters['q'].'%');
                    });
            });
        }

        return $query->with(['solicitante', 'responsavel'])->paginate($perPage ?? $this->defaultPerPage);
    }
}
