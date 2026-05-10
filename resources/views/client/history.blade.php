@extends('layouts.app')

@section('title', 'Historique - ESPRIT File d\'Attente')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Espace Client</p>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Historique de mes tickets</h1>
                <p class="mt-3 text-slate-600">Consultez tous vos tickets passés et leur statut.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:border-[#B91C1C] focus:ring-[#B91C1C]">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="serving" {{ request('status') === 'serving' ? 'selected' : '' }}>En service</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">Service</label>
                <select name="service_id" class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:border-[#B91C1C] focus:ring-[#B91C1C]">
                    <option value="">Tous les services</option>
                    @foreach(\App\Models\Service::where('active', 1)->get() as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des tickets -->
    @if($tickets->count() > 0)
        <div class="space-y-4">
            @foreach($tickets as $ticket)
                <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl
                                @if($ticket->status === 'completed') bg-green-100 text-green-700
                                @elseif($ticket->status === 'canceled') bg-red-100 text-red-700
                                @elseif($ticket->status === 'serving') bg-blue-100 text-blue-700
                                @else bg-yellow-100 text-yellow-700 @endif font-bold">
                                {{ $ticket->ticket_number }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg">{{ $ticket->service->name }}</h3>
                                <p class="text-sm text-slate-600">{{ $ticket->full_name }}</p>
                                @if($ticket->priority === 'vip')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 mt-1">
                                        <i class="fas fa-star mr-1"></i>VIP
                                    </span>
                                @elseif($ticket->priority === 'urgent')
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium mb-2
                                @if($ticket->status === 'completed') bg-green-100 text-green-800
                                @elseif($ticket->status === 'canceled') bg-red-100 text-red-800
                                @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if($ticket->status === 'completed') <i class="fas fa-check mr-1"></i>Terminé
                                @elseif($ticket->status === 'canceled') <i class="fas fa-times mr-1"></i>Annulé
                                @elseif($ticket->status === 'serving') <i class="fas fa-clock mr-1"></i>En service
                                @else <i class="fas fa-clock mr-1"></i>En attente @endif
                            </span>
                            <p class="text-sm text-slate-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500">Créé le</p>
                            <p class="font-medium text-slate-900">{{ $ticket->created_at->format('d/m/Y') }}</p>
                            <p class="text-slate-600">{{ $ticket->created_at->format('H:i') }}</p>
                        </div>

                        @if($ticket->started_at)
                            <div>
                                <p class="text-slate-500">Début service</p>
                                <p class="font-medium text-slate-900">{{ $ticket->started_at->format('d/m/Y') }}</p>
                                <p class="text-slate-600">{{ $ticket->started_at->format('H:i') }}</p>
                            </div>
                        @endif

                        @if($ticket->completed_at)
                            <div>
                                <p class="text-slate-500">Terminé le</p>
                                <p class="font-medium text-slate-900">{{ $ticket->completed_at->format('d/m/Y') }}</p>
                                <p class="text-slate-600">{{ $ticket->completed_at->format('H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Durée totale</p>
                                <p class="font-medium text-slate-900">
                                    @if($ticket->started_at)
                                        {{ $ticket->completed_at->diffInMinutes($ticket->started_at) }} min
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($ticket->logs->count() > 0)
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <h4 class="font-medium text-slate-900 mb-2">Historique des actions</h4>
                            <div class="space-y-2">
                                @foreach($ticket->logs->sortByDesc('created_at') as $log)
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-2 h-2 bg-[#B91C1C] rounded-full"></div>
                                        <span class="text-slate-600">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="font-medium text-slate-900">{{ $log->description }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-12 shadow-sm text-center">
            <i class="fas fa-history text-6xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">Aucun ticket trouvé</h3>
            <p class="text-slate-600 mb-6">Vous n'avez pas encore créé de ticket ou aucun ne correspond aux filtres.</p>
            <a href="{{ route('client.tickets.create') }}" class="btn-primary">
                Créer mon premier ticket
            </a>
        </div>
    @endif
</div>
@endsection