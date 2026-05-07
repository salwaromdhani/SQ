@extends('layouts.app')

@section('title', $service->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- En-tête -->
        <div class="card card-esprit mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-1">{{ $service->name }}</h3>
                        @if($service->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails -->
        <div class="card card-esprit mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <p><strong>Description :</strong></p>
                <p class="mb-3">{{ $service->description ?? 'Aucune description' }}</p>
                
                <p><strong>Créé le :</strong> {{ $service->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Dernière mise à jour :</strong> {{ $service->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Tickets liés (optionnel) -->
        @if($service->tickets->count() > 0)
        <div class="card card-esprit">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Tickets associés ({{ $service->tickets->count() }})</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($service->tickets->take(5) as $ticket)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $ticket->ticket_number }} - {{ $ticket->full_name }}</span>
                        <span class="badge badge-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
                    </li>
                    @endforeach
                </ul>
                @if($service->tickets->count() > 5)
                <small class="text-muted mt-2 d-block">+ {{ $service->tickets->count() - 5 }} autres tickets</small>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="col-lg-4">
        <div class="card card-esprit">
            <div class="card-body">
                <h6 class="card-title">Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST"
                          onsubmit="return confirm('Supprimer ce service ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection