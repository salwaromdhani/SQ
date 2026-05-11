@extends('layouts.app')

@section('title', 'Détails de l\'Agent - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.agents.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $agent->name }}</h1>
            <p class="mt-2 text-slate-600">Détails et informations de l'agent.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Informations générales</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Nom complet</dt>
                        <dd class="mt-1 text-slate-900">{{ $agent->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-slate-900">{{ $agent->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                        <dd class="mt-1 text-slate-900">{{ $agent->phone ?: 'Non spécifié' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Rôle</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                @if($agent->role === 'admin') bg-purple-100 text-purple-800
                                @elseif($agent->role === 'superviseur') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($agent->role) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Service affecté</dt>
                        <dd class="mt-1 text-slate-900">
                            @if($agent->service)
                                {{ $agent->service->name }}
                            @else
                                <span class="text-slate-500">Non affecté</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Date d'ajout</dt>
                        <dd class="mt-1 text-slate-900">{{ $agent->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Dernière modification</dt>
                        <dd class="mt-1 text-slate-900">{{ $agent->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card-panel">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.agents.edit', $agent) }}" class="btn-primary w-full text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier l'agent
                    </a>
                    <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer l'agent
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection