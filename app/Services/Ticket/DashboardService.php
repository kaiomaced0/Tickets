<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    public function handle(User $user): array
    {
        $baseQuery = Ticket::query();

        $counts = [
            'total' => (clone $baseQuery)->count(),
            'abertos' => (clone $baseQuery)->where('status', 'ABERTO')->count(),
            'em_andamento' => (clone $baseQuery)->where('status', 'EM_ANDAMENTO')->count(),
            'resolvidos' => (clone $baseQuery)->where('status', 'RESOLVIDO')->count(),
        ];

        // Cancelados: admin vê todos, user vê apenas os que ele solicitou
        $canceladosQuery = (clone $baseQuery)->where('status', 'CANCELADO');
        if (! $user->isAdmin()) {
            $canceladosQuery->where('solicitante_id', $user->id);
        }
        $canceladosCount = $canceladosQuery->count();

        // Só incluir cancelados se houver pelo menos 1
        if ($canceladosCount > 0) {
            $counts['cancelados'] = $canceladosCount;
        }

        $ultimosAbertos = Ticket::query()
            ->latest()
            ->limit(6)
            ->get();

        $envolvido = Ticket::query()
            ->where(function ($q) use ($user): void {
                $q->where('solicitante_id', $user->id)
                    ->orWhere('responsavel_id', $user->id);
            })
            ->latest()
            ->limit(6)
            ->get();

        return [
            'counts' => $counts,
            'latest_opened' => $ultimosAbertos,
            'user_involved' => $envolvido,
        ];
    }
}
