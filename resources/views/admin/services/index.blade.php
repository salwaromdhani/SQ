@extends('layouts.app')

@section('title', 'Gestion des Services - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Services</h1>
            <p class="mt-2 text-slate-600">Gérez les services disponibles pour les clients.</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nouveau service
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
                {{ $services->total() }} service{{ $services->total() > 1 ? 's' : '' }} trouvé{{ $services->total() > 1 ? 's' : '' }}
            </div>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                       class="rounded-3xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($services as $service)
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $service->active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            <i class="fas fa-cog text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $service->name }}</h3>
                            <p class="text-sm text-slate-600">{{ $service->description ?: 'Aucune description' }}</p>
                            <div class="mt-1 flex items-center gap-4 text-xs text-slate-500">
                                <span>{{ $service->tickets_count }} ticket{{ $service->tickets_count > 1 ? 's' : '' }}</span>
                                <span class="inline-flex items-center rounded-full px-2 py-1 {{ $service->active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $service->active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.services.show', $service) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
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
                    <i class="fas fa-cog text-4xl text-slate-400"></i>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun service</h3>
                    <p class="mt-2 text-slate-600">Commencez par créer votre premier service.</p>
                    <a href="{{ route('admin.services.create') }}" class="btn-primary mt-4 inline-block">
                        Créer un service
                    </a>
                </div>
            @endforelse
        </div>

        @if($services->hasPages())
            <div class="mt-8">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>
@endsection