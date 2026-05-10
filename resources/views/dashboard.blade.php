@extends('layouts.app')

@section('title', 'Dashboard - File d\'Attente ESPRIT')

@section('content')

<style>
    .stat-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    }
    .stat-card .icon-bg {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1;
    }
    .stat-card .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }
    .card-accent-red    { border-left: 5px solid #8B1E3F; }
    .card-accent-blue   { border-left: 5px solid #3B82F6; }
    .card-accent-green  { border-left: 5px solid #10B981; }
    .card-accent-yellow { border-left: 5px solid #F59E0B; }
    .card-accent-purple { border-left: 5px solid #8B5CF6; }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1A1A2E;
        border-left: 4px solid var(--primary);
        padding-left: 12px;
        margin-bottom: 1.2rem;
    }
    .dashboard-header {
        background: linear-gradient(135deg, #8B1E3F 0%, #6A1630 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(139,30,63,0.25);
    }
    .ticket-row:hover { background: #FFF5F7; }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .progress-thin { height: 6px; border-radius: 10px; }
    .service-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F0F0F0;
    }
    .service-item:last-child { border-bottom: none; }
    .quick-action-btn {
        border-radius: 14px;
        padding: 1rem;
        border: 2px dashed #E0E0E0;
        background: white;
        transition: all 0.3s;
        text-decoration: none;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .quick-action-btn:hover {
        border-color: #8B1E3F;
        color: #8B1E3F;
        background: #FFF5F7;
        transform: translateY(-3px);
    }
    .quick-action-btn i { font-size: 1.5rem; }
</style>

<!-- Header -->
<div class="dashboard-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h2 class="mb-1 fw-bold"><i class="fas fa-chart-pie me-2"></i>Tableau de Bord</h2>
        <p class="mb-0 opacity-75">Vue d'ensemble de la file d'attente virtuelle</p>
    </div>
    <div class="text-end">
        <div class="opacity-75 small"><i class="fas fa-calendar me-1"></i>{{ now()->format('d/m/Y') }}</div>
        <div class="opacity-75 small"><i class="fas fa-clock me-1"></i>{{ now()->format('H:i') }}</div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row g-4 mb-4">

    <!-- Total Tickets -->
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-red p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#FFF0F3;">
                    <i class="fas fa-ticket-alt" style="color:#8B1E3F;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#8B1E3F;">{{ $totalTickets ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">Total Tickets</div>
        </div>
    </div>

    <!-- En attente -->
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-yellow p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#FFFBEB;">
                    <i class="fas fa-hourglass-half" style="color:#F59E0B;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#F59E0B;">{{ $waitingTickets ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">En Attente</div>
        </div>
    </div>

    <!-- En cours -->
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-blue p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#EFF6FF;">
                    <i class="fas fa-spinner" style="color:#3B82F6;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#3B82F6;">{{ $processingTickets ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">En Cours</div>
        </div>
    </div>

    <!-- Terminés -->
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-green p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#ECFDF5;">
                    <i class="fas fa-check-circle" style="color:#10B981;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#10B981;">{{ $completedTickets ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">Terminés</div>
        </div>
    </div>
</div>

<!-- Ligne 2 : Agents + Services -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-purple p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#F5F3FF;">
                    <i class="fas fa-users" style="color:#8B5CF6;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#8B5CF6;">{{ $totalAgents ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">Agents</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-red p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#FFF0F3;">
                    <i class="fas fa-concierge-bell" style="color:#8B1E3F;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#8B1E3F;">{{ $totalServices ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">Services</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-green p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#ECFDF5;">
                    <i class="fas fa-percentage" style="color:#10B981;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#10B981;">
                {{ $totalTickets > 0 ? round(($completedTickets / $totalTickets) * 100) : 0 }}%
            </div>
            <div class="stat-label text-muted mt-1">Taux Résolution</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card card card-accent-yellow p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-bg" style="background:#FFFBEB;">
                    <i class="fas fa-ban" style="color:#EF4444;"></i>
                </div>
            </div>
            <div class="stat-number" style="color:#EF4444;">{{ $canceledTickets ?? 0 }}</div>
            <div class="stat-label text-muted mt-1">Annulés</div>
        </div>
    </div>
</div>

<!-- Ligne 3 : Tableau tickets récents + Répartition statuts -->
<div class="row g-4 mb-4">

    <!-- Tickets récents -->
    <div class="col-lg-7">
        <div class="card stat-card p-4 h-100">
            <div class="section-title"><i class="fas fa-list me-2"></i>Tickets Récents</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="font-size:0.82rem; color:#888; text-transform:uppercase;">
                            <th>#</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets ?? [] as $ticket)
                        <tr class="ticket-row">
                            <td><span class="fw-bold" style="color:#8B1E3F;">{{ $ticket->number ?? $ticket->id }}</span></td>
                            <td>{{ $ticket->client_name ?? 'N/A' }}</td>
                            <td>{{ $ticket->service->name ?? 'N/A' }}</td>
                            <td>
                                @if(($ticket->status ?? '') === 'waiting')
                                    <span class="status-badge badge-waiting">En attente</span>
                                @elseif(($ticket->status ?? '') === 'processing')
                                    <span class="status-badge badge-processing">En cours</span>
                                @elseif(($ticket->status ?? '') === 'completed')
                                    <span class="status-badge badge-completed">Terminé</span>
                                @else
                                    <span class="status-badge badge-canceled">Annulé</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ isset($ticket->created_at) ? $ticket->created_at->format('d/m H:i') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                Aucun ticket pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('tickets.index') }}" class="btn-esprit btn btn-sm">
                    Voir tous les tickets <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Répartition statuts -->
    <div class="col-lg-5">
        <div class="card stat-card p-4 h-100">
            <div class="section-title"><i class="fas fa-chart-bar me-2"></i>Répartition des Statuts</div>

            @php
                $total = $totalTickets ?? 1;
                $statuses = [
                    ['label' => 'En attente',  'value' => $waitingTickets ?? 0,    'color' => '#F59E0B', 'bg' => '#FFFBEB'],
                    ['label' => 'En cours',    'value' => $processingTickets ?? 0, 'color' => '#3B82F6', 'bg' => '#EFF6FF'],
                    ['label' => 'Terminés',    'value' => $completedTickets ?? 0,  'color' => '#10B981', 'bg' => '#ECFDF5'],
                    ['label' => 'Annulés',     'value' => $canceledTickets ?? 0,   'color' => '#EF4444', 'bg' => '#FEE2E2'],
                ];
            @endphp

            @foreach($statuses as $s)
            @php $pct = $total > 0 ? round(($s['value'] / $total) * 100) : 0; @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-600 small" style="color:#333;">{{ $s['label'] }}</span>
                    <span class="fw-bold small" style="color:{{ $s['color'] }};">{{ $s['value'] }} <span class="text-muted fw-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="progress progress-thin">
                    <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $s['color'] }}; border-radius:10px;"></div>
                </div>
            </div>
            @endforeach

            <!-- Services top -->
            <div class="section-title mt-4"><i class="fas fa-concierge-bell me-2"></i>Services Actifs</div>
            @forelse($topServices ?? [] as $service)
            <div class="service-item">
                <div>
                    <i class="fas fa-circle me-2" style="color:#8B1E3F; font-size:0.5rem;"></i>
                    <span class="small fw-600">{{ $service->name }}</span>
                </div>
                <span class="badge" style="background:#FFF0F3; color:#8B1E3F; border-radius:50px; padding:4px 10px; font-size:0.75rem;">
                    {{ $service->tickets_count ?? 0 }} tickets
                </span>
            </div>
            @empty
            <p class="text-muted small text-center mt-2">Aucun service disponible</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="card stat-card p-4 mb-2">
    <div class="section-title"><i class="fas fa-bolt me-2"></i>Actions Rapides</div>
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <a href="{{ route('tickets.create') }}" class="quick-action-btn w-100">
                <i class="fas fa-plus-circle" style="color:#8B1E3F;"></i>
                Nouveau Ticket
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('tickets.index') }}" class="quick-action-btn w-100">
                <i class="fas fa-list" style="color:#3B82F6;"></i>
                Tous les Tickets
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('agents.index') }}" class="quick-action-btn w-100">
                <i class="fas fa-users" style="color:#8B5CF6;"></i>
                Gérer Agents
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('services.index') }}" class="quick-action-btn w-100">
                <i class="fas fa-concierge-bell" style="color:#10B981;"></i>
                Gérer Services
            </a>
        </div>
    </div>
</div>

@endsection
