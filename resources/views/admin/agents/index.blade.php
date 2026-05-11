@extends('layouts.app')

@section('title', 'Gestion des Agents - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Agents</h1>
            <p class="mt-2 text-slate-600">Gérez les agents et leurs affectations aux services.</p>
        </div>
        <a href="{{ route('admin.agents.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nouvel agent
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-panel">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="text-sm text-slate-600">
                {{ $agents->total() }} agent{{ $agents->total() > 1 ? 's' : '' }} trouvé{{ $agents->total() > 1 ? 's' : '' }}
            </div>
            <form method="GET" class="flex gap-2">
                <select name="role" class="rounded-3xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="superviseur" {{ request('role') === 'superviseur' ? 'selected' : '' }}>Superviseur</option>
                    <option value="agent" {{ request('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                </select>
                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($agents as $agent)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">
                            <i class="fas fa-user-tie text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $agent->name }}</h3>
                            <p class="text-sm text-slate-600">{{ $agent->email }}</p>
                            <div class="mt-1 flex items-center gap-4 text-xs text-slate-500">
                                <span class="inline-flex items-center rounded-full px-2 py-1
                                    @if($agent->role === 'admin') bg-purple-100 text-purple-700
                                    @elseif($agent->role === 'superviseur') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ ucfirst($agent->role) }}
                                </span>
                                @if($agent->service)
                                    <span>{{ $agent->service->name }}</span>
                                @else
                                    <span class="text-slate-400">Non affecté</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.agents.show', $agent) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger text-sm px-3 py-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                    <i class="fas fa-user-tie text-4xl text-slate-400"></i>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun agent</h3>
                    <p class="mt-2 text-slate-600">Commencez par ajouter votre premier agent.</p>
                    <a href="{{ route('admin.agents.create') }}" class="btn-primary mt-4 inline-block">
                        Ajouter un agent
                    </a>
                </div>
            @endforelse
        </div>

        @if($agents->hasPages())
            <div class="mt-8">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection