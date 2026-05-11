<?php

namespace App\Http\Controllers;

use App\Models\TicketLog;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = TicketLog::with(['ticket.service'])
            ->when($request->ticket_id, function ($query) use ($request) {
                $query->where('ticket_id', $request->ticket_id);
            })
            ->latest()
            ->paginate(15);

        return view('admin.ticket_logs.index', compact('logs'));
    }

    public function create()
    {
        $tickets = Ticket::with('service')->latest()->get();

        return view('admin.ticket_logs.create', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'action' => 'required|string|max:50',
            'old_value' => 'nullable|string|max:255',
            'new_value' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:500',
        ]);

        TicketLog::create($validated);

        return redirect()->route('admin.ticket-logs.index')
            ->with('success', 'Log ajouté.');
    }

    public function show(TicketLog $ticketLog)
    {
        $ticketLog->load('ticket.service');

        return view('admin.ticket_logs.show', compact('ticketLog'));
    }

    public function edit(TicketLog $ticketLog)
    {
        $tickets = Ticket::with('service')->latest()->get();

        return view('admin.ticket_logs.edit', compact('ticketLog', 'tickets'));
    }

    public function update(Request $request, TicketLog $ticketLog)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'action' => 'required|string|max:50',
            'old_value' => 'nullable|string|max:255',
            'new_value' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:500',
        ]);

        $ticketLog->update($validated);

        return redirect()->route('admin.ticket-logs.show', $ticketLog)
            ->with('success', 'Log mis à jour.');
    }

    public function destroy(TicketLog $ticketLog)
    {
        $ticketLog->delete();

        return redirect()->route('admin.ticket-logs.index')
            ->with('success', 'Log supprimé.');
    }
}