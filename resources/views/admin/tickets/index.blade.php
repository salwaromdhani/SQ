@extends('layouts.app')

@section('title', 'Gestion des Tickets - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Tickets</h1>
            <p class="mt-2 text-slate-600">Gérez les tickets en attente, en cours et terminés.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="grid gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center">
            <div class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</div>
            <div class="text-sm text-slate-600">Total</div>
        </div>
        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-center">
            <div class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
            <div class="text-sm text-yellow-600">En attente</div>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-center">
            <div class="text-2xl font-bold text-blue-700">{{ $stats['serving'] }}</div>
            <div class="text-sm text-blue-600">En cours</div>
        </div>
        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-center">
            <div class="text-2xl font-bold text-green-700">{{ $stats['completed'] }}</div>
            <div class="text-sm text-green-600">Terminés</div>
        </div>
    </div>

    <div class="card-panel">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="text-sm text-slate-600">
                {{ $tickets->total() }} ticket{{ $tickets->total() > 1 ? 's' : '' }} trouvé{{ $tickets->total() > 1 ? 's' : '' }}
            </div>
            <form method="GET" class="flex gap-2">
                <select name="status" class="rounded-3xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="serving" {{ request('status') === 'serving' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Annulé</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                       class="rounded-3xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($tickets as $ticket)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl
                            @if($ticket->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($ticket->status === 'serving') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700 @endif">
                            <i class="fas fa-ticket-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $ticket->ticket_number }}</h3>
                            <p class="text-sm text-slate-600">{{ $ticket->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $ticket->service->name }}</p>
                            <div class="mt-1 flex items-center gap-4 text-xs text-slate-500">
                                <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                @if($ticket->estimated_wait_time)
                                    <span>{{ $ticket->estimated_wait_time }} min estimé</span>
                                @endif
                                <span class="inline-flex items-center rounded-full px-2 py-1
                                    @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($ticket->status === 'pending')
                            <form method="POST" action="{{ route('admin.tickets.serve', $ticket) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-primary text-sm px-3 py-2" title="Appeler ce ticket">
                                    <i class="fas fa-play"></i>
                                </button>
                            </form>
                        @elseif($ticket->status === 'serving')
                            <form method="POST" action="{{ route('admin.tickets.complete', $ticket) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-success text-sm px-3 py-2" title="Marquer comme terminé">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger text-sm px-3 py-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                    <i class="fas fa-ticket-alt text-4xl text-slate-400"></i>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun ticket</h3>
                    <p class="mt-2 text-slate-600">Il n'y a actuellement aucun ticket dans le système.</p>
                </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
            <div class="mt-8">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection