<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->ticketService->getAll(),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->getById($id);

        return response()->json([
            'success' => true,
            'data'    => $ticket,
        ]);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->create(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data'    => $ticket,
        ], 201);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->update($ticket, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => $ticket,
        ]);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->ticketService->delete($ticket);

        return response()->json([
            'success' => true,
            'message' => 'Ticket eliminado correctamente',
        ]);
    }
}
