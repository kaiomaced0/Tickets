<?php

namespace App\Services\Ticket;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TicketFilterService
{
    /**
     * Processa os filtros da requisição web para a listagem de tickets
     */
    public function processWebFilters(array $rawFilters): array
    {
        $filters = [
            'status' => $rawFilters['status'] ?? null,
            'prioridade' => $rawFilters['prioridade'] ?? null,
            'q' => $rawFilters['q'] ?? null,
        ];

        // Processar filtros de usuário (solicitante/responsável)
        if (!empty($rawFilters['user_type']) && !empty($rawFilters['user_filter'])) {
            $userType = $rawFilters['user_type']; // 'solicitante' ou 'responsavel'
            $userFilter = $rawFilters['user_filter'];

            if ($userFilter === 'me') {
                // Filtrar pelo usuário logado
                $filters[$userType . '_id'] = Auth::id();
            } else {
                // Buscar usuário por nome
                $user = User::where('name', 'like', '%' . $userFilter . '%')->first();
                if ($user) {
                    $filters[$userType . '_id'] = $user->id;
                }
            }
        }

        // Remover valores nulos/vazios
        return array_filter($filters, fn($value) => !is_null($value) && $value !== '');
    }
}
