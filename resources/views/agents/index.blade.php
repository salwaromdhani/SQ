@extends('layouts.app')

@section('title', 'Liste des Agents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Agents</h2>
    <a href="{{ route('agents.create') }}" class="btn btn-esprit">
        <i class="fas fa-plus me-1"></i>Nouvel Agent
    </a>
</div>

<!-- Filtres -->
<div class="card card-esprit mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('agents.index') }}" class="row g-3">
            <div class="col-md-4">
                <select name="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Administrateur</option>
                    <option value="agent" {{ request('role')==='agent'?'selected':'' }}>Agent</option>
                    <option value="superviseur" {{ request('role')==='superviseur'?'selected':'' }}>Superviseur</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-esprit w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
            @if(request('role'))
            <div class="col-md-2">
                <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary w-100">
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

<!-- Tableau -->
<div class="card card-esprit">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Service</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td><strong>{{ $agent->name }}</strong></td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone ?? '–' }}</td>
                    <td>
                        @if($agent->role === 'admin')
                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                        @elseif($agent->role === 'superviseur')
                            <span class="badge bg-primary"><i class="fas fa-user-tie me-1"></i>Superviseur</span>
                        @else
                            <span class="badge bg-info"><i class="fas fa-user me-1"></i>Agent</span>
                        @endif
                    </td>
                    <td>{{ $agent->service->name ?? 'Non affecté' }}</td>
                    <td>
                        @if($agent->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('agents.destroy', $agent) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer cet agent ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Aucun agent trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $agents->links() }}
</div>
@endsection