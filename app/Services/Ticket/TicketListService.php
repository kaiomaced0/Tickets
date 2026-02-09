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

    public function handle(?User $user = null, ?int $perPage = null): LengthAwarePaginator
    {
        $query = Ticket::query()->latest();

        if ($user) {
            $query->whereBelongsTo($user);
        }

        return $query->paginate($perPage ?? $this->defaultPerPage);
    }
}
