<?php

namespace App\Http\Controllers;

use App\Models\TicketLog;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = TicketLog::with('ticket')
            ->when($request->ticket_id, function ($query) use ($request) {
                $query->where('ticket_id', $request->ticket_id);
            })
            ->latest()
            ->paginate(15);

        return view('ticket_logs.index', compact('logs'));
    }

    public function create()
    {
        $tickets = Ticket::latest()->get();
        return view('ticket_logs.create', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'action' => 'required|string|max:50',
            'comment' => 'nullable|string|max:500',
        ]);

        TicketLog::create($validated);

        return redirect()->route('ticket-logs.index')
            ->with('success', 'Log ajouté.');
    }

    public function show(TicketLog $ticketLog)
    {
        return view('ticket_logs.show', compact('ticketLog'));
    }
}