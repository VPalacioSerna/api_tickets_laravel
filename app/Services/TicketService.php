<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketService
{
    public function getAll(): Collection
    {
        return Ticket::with('user')->get();
    }

    public function getById(int $id): Ticket
    {
        return Ticket::with('user')->findOrFail($id);
    }

    public function create(array $data, int $userId): Ticket
    {
        return Ticket::create([
            'user_id'     => $userId,
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => $data['status'] ?? 'open',
        ]);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket->fresh();
    }

    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
