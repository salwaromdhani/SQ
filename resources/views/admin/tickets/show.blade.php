@extends('layouts.app')

@section('title', 'Détails du Ticket - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tickets.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $ticket->ticket_number }}</h1>
            <p class="mt-2 text-slate-600">Détails et informations du ticket.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Informations générales</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Numéro de ticket</dt>
                        <dd class="mt-1 text-slate-900 font-mono">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Nom complet</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->email ?: 'Non spécifié' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->phone ?: 'Non spécifié' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Pays</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->country }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Service</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->service->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Statut</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Priorité</dt>
                        <dd class="mt-1 text-slate-900">{{ ucfirst($ticket->priority) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Temps d'attente estimé</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->estimated_wait_time ?: 0 }} minutes</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Date de création</dt>
                        <dd class="mt-1 text-slate-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($ticket->started_at)
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Début du service</dt>
                            <dd class="mt-1 text-slate-900">{{ $ticket->started_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($ticket->completed_at)
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Terminé le</dt>
                            <dd class="mt-1 text-slate-900">{{ $ticket->completed_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($ticket->arrival_notified_at)
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Notification d'arrivée</dt>
                            <dd class="mt-1 text-slate-900">{{ $ticket->arrival_notified_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Historique des actions</h2>
                @if($ticket->logs()->exists())
                    <div class="space-y-3">
                        @foreach($ticket->logs()->latest()->get() as $log)
                            <div class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FEE2E2] text-[#B91C1C]">
                                    <i class="fas fa-history text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900">{{ $log->action }}</p>
                                    <p class="text-xs text-slate-600">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                    @if($log->details)
                                        <p class="text-xs text-slate-500 mt-1">{{ $log->details }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500">Aucune action enregistrée pour ce ticket.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($ticket->status === 'pending')
                        <form method="POST" action="{{ route('admin.tickets.serve', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary w-full">
                                <i class="fas fa-play mr-2"></i>
                                Appeler ce ticket
                            </button>
                        </form>
                    @elseif($ticket->status === 'serving')
                        <form method="POST" action="{{ route('admin.tickets.complete', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-success w-full">
                                <i class="fas fa-check mr-2"></i>
                                Marquer comme terminé
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer le ticket
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Position dans la file</h2>
                @php
                    $position = \App\Models\Ticket::where('service_id', $ticket->service_id)
                        ->where('status', 'pending')
                        ->where('created_at', '<=', $ticket->created_at)
                        ->count();
                @endphp
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-900">{{ $position }}</div>
                    <div class="text-sm text-slate-600">ème dans la file</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection