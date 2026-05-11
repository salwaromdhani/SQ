@extends('layouts.app')

@section('title', 'Détails de l\'Action - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.ticket-logs.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $ticketLog->action }}</h1>
                <p class="mt-2 text-slate-600">Détails de l'action effectuée sur le ticket.</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.ticket-logs.edit', $ticketLog) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <form action="{{ route('admin.ticket-logs.destroy', $ticketLog) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce log ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Informations de l'action</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Action</dt>
                        <dd class="mt-1 text-slate-900 font-medium">{{ $ticketLog->action }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Détails</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticketLog->details ?: 'Aucun détail' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Date et heure</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticketLog->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Ticket concerné</h2>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-medium text-slate-500">Numéro du ticket</span>
                        <p class="text-slate-900 font-mono">{{ $ticketLog->ticket->ticket_number }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-slate-500">Client</span>
                        <p class="text-slate-900">{{ $ticketLog->ticket->full_name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-slate-500">Service</span>
                        <p class="text-slate-900">{{ $ticketLog->ticket->service->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-slate-500">Statut du ticket</span>
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                            @if($ticketLog->ticket->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($ticketLog->ticket->status === 'serving') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($ticketLog->ticket->status) }}
                        </span>
                    </div>
                    <div class="pt-4">
                        <a href="{{ route('admin.tickets.show', $ticketLog->ticket) }}" class="btn-primary">
                            <i class="fas fa-eye mr-2"></i>
                            Voir le ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection