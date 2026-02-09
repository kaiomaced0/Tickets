<?php

namespace App\Services\Ticket;

use App\Models\Ticket;
use App\Models\User;

class TicketCreateService
{
    public function handle(array $data, User $user): Ticket
    {
        return Ticket::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'open',
            'priority' => $data['priority'] ?? 'medium',
            'due_date' => $data['due_date'] ?? null,
            'closed_at' => $data['closed_at'] ?? null,
        ]);
    }
}
