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
        $stats = [
            'services' => Service::count(),
            'agents' => Agent::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'serving' => Ticket::where('status', 'serving')->count(),
            'history' => TicketLog::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
