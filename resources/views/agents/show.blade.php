@extends('layouts.app')

@section('title', $agent->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- En-tête -->
        <div class="card card-esprit mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-1"><i class="fas fa-user me-2"></i>{{ $agent->name }}</h3>
                        @if($agent->role === 'admin')
                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Administrateur</span>
                        @elseif($agent->role === 'superviseur')
                            <span class="badge bg-primary"><i class="fas fa-user-tie me-1"></i>Superviseur</span>
                        @else
                            <span class="badge bg-info"><i class="fas fa-user me-1"></i>Agent</span>
                        @endif
                        @if($agent->active)
                            <span class="badge bg-success ms-2">Actif</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inactif</span>
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Email :</strong></p>
                        <p>{{ $agent->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Téléphone :</strong></p>
                        <p>{{ $agent->phone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Service affecté :</strong></p>
                        <p>{{ $agent->service->name ?? 'Non affecté' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Créé le :</strong></p>
                        <p>{{ $agent->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <p><strong>Dernière mise à jour :</strong> {{ $agent->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="col-lg-4">
        <div class="card card-esprit">
            <div class="card-body">
                <h6 class="card-title">Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('agents.edit', $agent) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <form action="{{ route('agents.destroy', $agent) }}" method="POST"
                          onsubmit="return confirm('Supprimer cet agent ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                    <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection