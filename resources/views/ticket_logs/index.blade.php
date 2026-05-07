@extends('layouts.app')

@section('title', 'Historique des Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history me-2"></i>Journal d'activité</h2>
    <a href="{{ route('ticket-logs.create') }}" class="btn btn-esprit">
        <i class="fas fa-plus me-1"></i>Ajouter une entrée
    </a>
</div>

<!-- Filtres -->
<div class="card card-esprit mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('ticket-logs.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="number" name="ticket_id" class="form-control" 
                       placeholder="Filtrer par ID de Ticket (ex: 1)" 
                       value="{{ request('ticket_id') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-esprit w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            @if(request('ticket_id'))
            <div class="col-md-2">
                <a href="{{ route('ticket-logs.index') }}" class="btn btn-outline-secondary w-100">
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
                    <th>ID Log</th>
                    <th>Ticket Lié</th>
                    <th>Action</th>
                    <th>Ancienne Valeur</th>
                    <th>Nouvelle Valeur</th>
                    <th>Commentaire</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $log->ticket_id) }}" class="badge bg-secondary text-decoration-none">
                            TKT #{{ $log->ticket_id }}
                        </a>
                    </td>
                    <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span></td>
                    <td>{{ $log->old_value ?? '-' }}</td>
                    <td>{{ $log->new_value ?? '-' }}</td>
                    <td>{{ Str::limit($log->comment, 30) }}</td>
                    <td class="text-end">
                        <a href="{{ route('ticket-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-history fa-2x mb-2 d-block"></i>
                        Aucune activité enregistrée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection