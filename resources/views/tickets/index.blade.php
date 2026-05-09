@extends('layouts.app')

@section('title', 'Tableau de bord des tickets')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600">Administration</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Gestion des tickets</h1>
            <p class="mt-2 text-slate-600">Filtrez, suivez et traitez les tickets en quelques clics.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.services.index') }}" class="btn-secondary">Services</a>
            <a href="{{ route('admin.tickets.index') }}" class="btn-primary">Actualiser</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card-panel">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Total</p>
            <p class="mt-3 text-4xl font-semibold text-slate-900">{{ $stats['total'] }}</p>
            <p class="mt-2 text-sm text-slate-500">Tickets créés</p>
        </div>
        <div class="card-panel">
            <p class="text-sm uppercase tracking-[0.24em] text-amber-600">En attente</p>
            <p class="mt-3 text-4xl font-semibold text-slate-900">{{ $stats['pending'] }}</p>
            <p class="mt-2 text-sm text-slate-500">En attente de traitement</p>
        </div>
        <div class="card-panel">
            <p class="text-sm uppercase tracking-[0.24em] text-sky-600">En cours</p>
            <p class="mt-3 text-4xl font-semibold text-slate-900">{{ $stats['serving'] }}</p>
            <p class="mt-2 text-sm text-slate-500">Tickets actuellement servis</p>
        </div>
        <div class="card-panel">
            <p class="text-sm uppercase tracking-[0.24em] text-emerald-600">Terminés</p>
            <p class="mt-3 text-4xl font-semibold text-slate-900">{{ $stats['completed'] }}</p>
            <p class="mt-2 text-sm text-slate-500">Tickets finalisés</p>
        </div>
    </div>

    <div class="card-panel">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid gap-4 lg:grid-cols-[1.5fr_1fr_0.8fr_0.8fr] items-end">
            <div class="form-field">
                <label for="search" class="form-label">Recherche</label>
                <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="Numéro, nom, email, service" class="form-input" />
            </div>
            <div class="form-field">
                <label for="status" class="form-label">Statut</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="serving" {{ request('status') === 'serving' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminés</option>
                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Annulés</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-primary w-full">Filtrer</button>
            </div>
            <div>
                <a href="{{ route('admin.tickets.index') }}" class="btn-secondary w-full">Réinitialiser</a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-panel overflow-x-auto">
        <table class="min-w-full table-auto border-separate border-spacing-0 text-left">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="border-b border-slate-200 px-4 py-3">Numéro</th>
                    <th class="border-b border-slate-200 px-4 py-3">Client</th>
                    <th class="border-b border-slate-200 px-4 py-3">Service</th>
                    <th class="border-b border-slate-200 px-4 py-3">Statut</th>
                    <th class="border-b border-slate-200 px-4 py-3">Priorité</th>
                    <th class="border-b border-slate-200 px-4 py-3">Créé le</th>
                    <th class="border-b border-slate-200 px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($tickets as $ticket)
                <tr>
                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $ticket->ticket_number }}</td>
                    <td class="px-4 py-4 text-slate-700">
                        {{ $ticket->full_name }}<br>
                        <span class="text-sm text-slate-500">{{ $ticket->email }}</span>
                    </td>
                    <td class="px-4 py-4 text-slate-700">{{ $ticket->service->name ?? 'Sans service' }}</td>
                    <td class="px-4 py-4">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'serving' => 'bg-sky-100 text-sky-700',
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'canceled' => 'bg-rose-100 text-rose-700',
                            ];
                        @endphp
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusClasses[$ticket->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $ticket->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ $ticket->priority === 'urgent' ? 'Urgent' : 'Normal' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-slate-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-4 text-right space-x-2">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.tickets.edit', $ticket) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($ticket->status === 'pending')
                            <form action="{{ route('admin.tickets.serve', $ticket) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 transition hover:bg-blue-200" title="Passer en cours">
                                    <i class="fas fa-play"></i>
                                </button>
                            </form>
                        @endif
                        @if($ticket->status === 'serving')
                            <form action="{{ route('admin.tickets.complete', $ticket) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 transition hover:bg-emerald-200" title="Terminer">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce ticket ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 transition hover:bg-rose-200" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-16 text-center text-slate-500">
                        <div class="space-y-2">
                            <div class="mx-auto inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <i class="fas fa-inbox text-2xl"></i>
                            </div>
                            <p class="text-sm">Aucun ticket trouvé.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endsection