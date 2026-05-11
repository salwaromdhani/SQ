<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TicketLogController;
use App\Models\Ticket;

Route::get('/', function () {
    $stats = [
        'total' => Ticket::count(),
        'pending' => Ticket::where('status', 'pending')->count(),
        'serving' => Ticket::where('status', 'serving')->count(),
        'average_wait' => round(Ticket::whereIn('status', ['pending', 'serving'])->avg('estimated_wait_time') ?: 0),
    ];

    return view('home', compact('stats'));
})->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ROUTES CLIENT (PUBLIC)
// ============================================
Route::prefix('client')->name('client.')->group(function () {
    // Création de tickets
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    // Affichage du ticket après création
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    // File d'attente en temps réel
    Route::get('/queue/live', [TicketController::class, 'liveQueue'])->name('queue.live');
});

// ============================================
// ROUTES ADMIN (PROTÉGÉES)
// ============================================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD complet pour tous les modèles
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('tickets', TicketController::class)->except(['create', 'store']); // Sauf création publique
    Route::resource('services', ServiceController::class);
    Route::resource('agents', AgentController::class);
    Route::resource('ticket-logs', TicketLogController::class);

    // Actions supplémentaires pour tickets
    Route::patch('/tickets/{ticket}/serve', [TicketController::class, 'serve'])->name('tickets.serve');
    Route::patch('/tickets/{ticket}/complete', [TicketController::class, 'complete'])->name('tickets.complete');
});

// ============================================
// ROUTES EMPLOYÉ (PROTÉGÉES)
// ============================================
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::post('/call-next', [EmployeeController::class, 'callNext'])->name('call-next');
    Route::patch('/tickets/{ticket}/complete-service', [EmployeeController::class, 'completeService'])->name('tickets.complete-service');
    Route::patch('/tickets/{ticket}/cancel', [EmployeeController::class, 'cancelTicket'])->name('tickets.cancel');
    Route::get('/serving', [EmployeeController::class, 'servingTickets'])->name('serving');
});

// ============================================
// ROUTES CLIENT (PROTÉGÉES)
// ============================================
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [ClientController::class, 'history'])->name('history');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
});

// ============================================
// API POUR LES GRAPHIQUES (PROTÉGÉ)
// ============================================
Route::middleware(['auth'])->prefix('api/charts')->name('api.charts.')->group(function () {
    Route::get('/tickets-by-status', [ChartController::class, 'ticketsByStatus'])->name('tickets-by-status');
    Route::get('/tickets-by-service', [ChartController::class, 'ticketsByService'])->name('tickets-by-service');
    Route::get('/tickets-by-day', [ChartController::class, 'ticketsByDay'])->name('tickets-by-day');
    Route::get('/average-wait-time', [ChartController::class, 'averageWaitTime'])->name('average-wait-time');
    Route::get('/tickets-by-hour', [ChartController::class, 'ticketsByHour'])->name('tickets-by-hour');
    Route::get('/dashboard-stats', [ChartController::class, 'dashboardStats'])->name('dashboard-stats');
});

// ============================================
// QR CODES (PROTÉGÉ)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets/{ticket}/qr-code', [QrCodeController::class, 'show'])->name('tickets.qr-code');
    Route::get('/tickets/{ticket}/qr-code/download', [QrCodeController::class, 'download'])->name('tickets.qr-code.download');
    Route::get('/tickets/{ticket}/qr-code/page', [QrCodeController::class, 'page'])->name('tickets.qr-code.page');
});

// ============================================
// API POUR POLLING TEMPS RÉEL (PUBLIC)
// ============================================
Route::get('/api/tickets/{ticket}/status', [TicketController::class, 'apiStatus'])->name('api.tickets.status');
// ============================================
// THÈME (BASCULER MODE SOMBRE/CLAIR)
// ============================================
Route::post('/theme/toggle', function () {
    // Le middleware DarkModeMiddleware gère cette route
})->name('theme.toggle')->middleware('dark_mode');