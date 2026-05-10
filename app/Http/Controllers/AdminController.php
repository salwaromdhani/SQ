<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    $avgWait = \App\Models\Ticket::whereNotNull('created_at')
        ->get()
        ->avg(function ($ticket) {
            return now()->diffInMinutes($ticket->created_at);
        });

    $stats = [
        'total' => \App\Models\Ticket::count(),
        'pending' => \App\Models\Ticket::where('status', 'pending')->count(),
        'serving' => \App\Models\Ticket::where('status', 'serving')->count(),
        'average_wait' => round($avgWait ?? 0),
    ];

    return view('admin.dashboard', compact('stats'));
}
}
