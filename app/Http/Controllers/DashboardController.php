<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Agent;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets      = Ticket::count();
        $waitingTickets    = Ticket::where('status', 'waiting')->count();
        $processingTickets = Ticket::where('status', 'processing')->count();
        $completedTickets  = Ticket::where('status', 'completed')->count();
        $canceledTickets   = Ticket::where('status', 'canceled')->count();
        $totalAgents       = Agent::count();
        $totalServices     = Service::count();

        $recentTickets = Ticket::with('service')
            ->latest()
            ->take(6)
            ->get();

        $topServices = Service::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalTickets',
            'waitingTickets',
            'processingTickets',
            'completedTickets',
            'canceledTickets',
            'totalAgents',
            'totalServices',
            'recentTickets',
            'topServices'
        ));
    }
}
