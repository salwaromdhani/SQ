@extends('layouts.app')

@section('title', 'Liste des Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-ticket-alt me-2"></i>Tickets</h2>
    <a href="{{ route('admin.services.index') }}" class="btn btn-esprit">
        <i class="fas fa-cogs me-1"></i>Gérer les Services
    </a>
</div>

<!-- Formulaire de recherche et filtres -->
<div class="card card-esprit mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" 
                       placeholder="Rechercher (numéro, nom, email...)" 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="waiting" {{ request('status')==='waiting'?'selected':'' }}>En attente</option>
                    <option value="processing" {{ request('status')==='processing'?'selected':'' }}>En cours</option>
                    <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Terminé</option>
                    <option value="canceled" {{ request('status')==='canceled'?'selected':'' }}>Annulé</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-esprit w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            @if(request()->anyFilled(['search','status']))
            <div class="col-md-2">
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Messages de succès -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Tableau des tickets -->
<div class="card card-esprit">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Créé le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td><strong>{{ $ticket->ticket_number }}</strong></td>
                        <td>
                            {{ $ticket->full_name }}<br>
                            <small class="text-muted">{{ $ticket->email }}</small>
                        </td>
                        <td>{{ $ticket->service->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $ticket->status }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>
                            @if($ticket->priority === 'urgent')
                                <span class="badge bg-danger"><i class="fas fa-bolt me-1"></i>Urgent</span>
                            @else
                                <span class="badge bg-secondary">Normal</span>
                            @endif
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce ticket ?')">
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
                            Aucun ticket trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $tickets->links() }}
</div>
@endsection