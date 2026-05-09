@extends('layouts.app')

@section('title', 'Liste des Agents')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.24em] text-indigo-600">Gestion des agents</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900">Agents</h1>
            <p class="mt-2 text-slate-600">Visualisez, filtrez et gérez les agents actifs et assignés.</p>
        </div>
        <a href="{{ route('agents.create') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i>Nouvel Agent
        </a>
    </div>

    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('agents.index') }}" class="grid gap-4 lg:grid-cols-[1.5fr_0.7fr_0.7fr] items-end">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">Rôle</label>
                <select name="role" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Administrateur</option>
                    <option value="agent" {{ request('role')==='agent'?'selected':'' }}>Agent</option>
                    <option value="superviseur" {{ request('role')==='superviseur'?'selected':'' }}>Superviseur</option>
                </select>
            </div>
            <button type="submit" class="rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                <i class="fas fa-filter mr-2"></i>Filtrer
            </button>
            @if(request('role'))
                <a href="{{ route('agents.index') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 bg-slate-50 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    <i class="fas fa-times mr-2"></i>Réinitialiser
                </a>
            @endif
        </form>
    </div>

@if(session('success'))
    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900 shadow-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm overflow-x-auto">
    <table class="min-w-full table-auto text-left">
        <thead class="bg-slate-100 text-slate-700">
            <tr>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Nom</th>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Email</th>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Téléphone</th>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Rôle</th>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Service</th>
                <th class="border-b border-slate-200 px-4 py-3 text-sm font-semibold">Statut</th>
                <th class="border-b border-slate-200 px-4 py-3 text-right text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 text-slate-700">
            @forelse($agents as $agent)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-4 font-semibold text-slate-900">{{ $agent->name }}</td>
                <td class="px-4 py-4">{{ $agent->email }}</td>
                <td class="px-4 py-4">{{ $agent->phone ?? '–' }}</td>
                <td class="px-4 py-4">
                    @if($agent->role === 'admin')
                        <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-rose-700">Administrateur</span>
                    @elseif($agent->role === 'superviseur')
                        <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">Superviseur</span>
                    @else
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Agent</span>
                    @endif
                </td>
                <td class="px-4 py-4">{{ $agent->service->name ?? 'Non affecté' }}</td>
                <td class="px-4 py-4">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $agent->active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                        {{ $agent->active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="px-4 py-4 text-right space-x-2">
                    <a href="{{ route('agents.show', $agent) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('agents.edit', $agent) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('agents.destroy', $agent) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet agent ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 transition hover:bg-rose-200" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                    <div class="space-y-3">
                        <div class="mx-auto inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <i class="fas fa-inbox text-2xl"></i>
                        </div>
                        <p>Aucun agent trouvé.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $agents->links() }}
</div>
@endsection