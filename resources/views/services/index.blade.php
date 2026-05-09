@extends('layouts.app')

@section('title', 'Gestion des services')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600">Administration</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Services</h1>
            <p class="mt-2 text-slate-600">Gérez les services disponibles et visualisez leur activité.</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">Nouveau service</a>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900">
            {{ session('error') }}
        </div>
    @endif

    <div class="card-panel">
        <form method="GET" action="{{ route('admin.services.index') }}" class="grid gap-4 lg:grid-cols-[1.5fr_0.8fr_0.8fr] items-end">
            <div class="form-field">
                <label for="search" class="form-label">Rechercher</label>
                <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="Nom ou description" class="form-input" />
            </div>
            <div>
                <button type="submit" class="btn-primary w-full">Rechercher</button>
            </div>
            <div>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary w-full">Réinitialiser</a>
            </div>
        </form>
    </div>

    <div class="card-panel overflow-x-auto">
        <table class="min-w-full text-left">
            <thead class="border-b border-slate-200 text-slate-700">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Tickets</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($services as $service)
                <tr>
                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $service->name }}</td>
                    <td class="px-4 py-4 text-slate-600">{{ Illuminate\Support\Str::limit($service->description ?? 'Aucune description', 50) }}</td>
                    <td class="px-4 py-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $service->active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $service->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-slate-700">{{ $service->tickets_count }}</td>
                    <td class="px-4 py-4 text-right">
                        <a href="{{ route('admin.services.show', $service) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce service ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 transition hover:bg-rose-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-16 text-center text-slate-500">Aucun service trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $services->links() }}
    </div>
</div>
@endsection