@extends('layouts.app')

@section('title', 'Détails du Service - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.services.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $service->name }}</h1>
            <p class="mt-2 text-slate-600">Détails et statistiques du service.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Informations générales</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Nom</dt>
                        <dd class="mt-1 text-slate-900">{{ $service->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Description</dt>
                        <dd class="mt-1 text-slate-900">{{ $service->description ?: 'Aucune description' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Statut</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $service->active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800' }}">
                                {{ $service->active ? 'Actif' : 'Inactif' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Date de création</dt>
                        <dd class="mt-1 text-slate-900">{{ $service->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Dernière modification</dt>
                        <dd class="mt-1 text-slate-900">{{ $service->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Tickets récents</h2>
                @if($service->tickets()->exists())
                    <div class="space-y-3">
                        @foreach($service->tickets()->latest()->take(5) as $ticket)
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $ticket->ticket_number }}</p>
                                    <p class="text-sm text-slate-600">{{ $ticket->full_name }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                    @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.tickets.index', ['service_id' => $service->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Voir tous les tickets →
                        </a>
                    </div>
                @else
                    <p class="text-slate-500">Aucun ticket pour ce service.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Statistiques</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Total tickets</span>
                        <span class="font-semibold text-slate-900">{{ $service->tickets()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">En attente</span>
                        <span class="font-semibold text-yellow-600">{{ $service->tickets()->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">En cours</span>
                        <span class="font-semibold text-blue-600">{{ $service->tickets()->where('status', 'serving')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Terminés</span>
                        <span class="font-semibold text-green-600">{{ $service->tickets()->where('status', 'completed')->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="card-panel">
                <div class="flex gap-2">
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary flex-1 text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection