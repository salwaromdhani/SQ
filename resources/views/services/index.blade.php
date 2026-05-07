@extends('layouts.app')

@section('title', 'Liste des Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-concierge-bell me-2"></i>Services</h2>
    <a href="{{ route('services.create') }}" class="btn btn-esprit">
        <i class="fas fa-plus me-1"></i>Nouveau Service
    </a>
</div>

<!-- Recherche -->
<div class="card card-esprit mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('services.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" 
                       placeholder="Rechercher un service..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-esprit w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Tableau -->
<div class="card card-esprit">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Tickets</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td><strong>{{ $service->name }}</strong></td>
                    <td>{{ Str::limit($service->description, 50) ?? '–' }}</td>
                    <td>
                        @if($service->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $service->tickets_count ?? 0 }}</td>
                    <td class="text-end">
                        <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce service ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Aucun service trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $services->links() }}
</div>
@endsection