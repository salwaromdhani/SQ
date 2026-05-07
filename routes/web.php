<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\TicketLogController;

Route::get('/', function () {
    return view('home');
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
    Route::resource('tickets', TicketController::class)->except(['create', 'store']); // Sauf création publique
    Route::resource('services', ServiceController::class);
    Route::resource('agents', AgentController::class);
    Route::resource('ticket-logs', TicketLogController::class);

    // Actions supplémentaires pour tickets
    Route::patch('/tickets/{ticket}/serve', [TicketController::class, 'serve'])->name('tickets.serve');
    Route::patch('/tickets/{ticket}/complete', [TicketController::class, 'complete'])->name('tickets.complete');
});

// ============================================
// API POUR POLLING TEMPS RÉEL (PUBLIC)
// ============================================
Route::get('/api/tickets/{ticket}/status', [TicketController::class, 'apiStatus'])->name('api.tickets.status');