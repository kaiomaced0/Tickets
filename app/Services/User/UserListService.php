<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserListService
{
    public function __construct(private readonly int $defaultPerPage = 15)
    {
    }

    public function handle(array $filters = [], ?int $perPage = null): LengthAwarePaginator
    {
        $query = User::query();

        // Filtro de busca
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro de role
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // Filtro de status (ativo/inativo)
        if (!empty($filters['active']) && $filters['active'] !== '') {
            $active = $filters['active'] === '1' || $filters['active'] === true;
            $query->where('active', $active);
        }

        return $query->orderBy('name')->paginate($perPage ?? $this->defaultPerPage);
    }
}
