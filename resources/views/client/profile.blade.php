@extends('layouts.app')

@section('title', 'Mon Profil - ESPRIT File d\'Attente')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Espace Client</p>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Mon profil</h1>
                <p class="mt-3 text-slate-600">Gérez vos informations personnelles et vos préférences.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Informations du profil -->
        <div class="lg:col-span-2">
            <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Informations personnelles</h2>

                <form action="{{ route('client.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="form-field">
                            <label for="name" class="form-label">Nom complet <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input" required />
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="email" class="form-label">Adresse email <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field md:col-span-2">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+216 00 000 000" />
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Mettre à jour le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques du compte -->
        <div class="space-y-6">
            <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Statistiques du compte</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Membre depuis</span>
                        <span class="font-medium text-slate-900">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Total tickets</span>
                        <span class="font-medium text-slate-900">{{ \App\Models\Ticket::where('email', $user->email)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Tickets terminés</span>
                        <span class="font-medium text-slate-900">{{ \App\Models\Ticket::where('email', $user->email)->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Temps d'attente moyen</span>
                        <span class="font-medium text-slate-900">
                            {{ round(\App\Models\Ticket::where('email', $user->email)->avg('estimated_wait_time') ?: 0) }} min
                        </span>
                    </div>
                </div>
            </div>

            <!-- Préférences -->
            <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Préférences</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Notifications email</span>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                            <input type="checkbox" name="email_notifications" id="email_notifications"
                                   class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                   checked />
                            <label for="email_notifications"
                                   class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Notifications SMS</span>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                            <input type="checkbox" name="sms_notifications" id="sms_notifications"
                                   class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                            <label for="sms_notifications"
                                   class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="rounded-[2rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Sécurité</h3>
                <div class="space-y-3">
                    <button type="button" class="w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                        <i class="fas fa-key mr-2"></i>Changer le mot de passe
                    </button>
                    <button type="button" class="w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                        <i class="fas fa-shield-alt mr-2"></i>Paramètres de confidentialité
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.toggle-checkbox:checked {
    @apply: bg-[#B91C1C];
    transform: translateX(100%);
}

.toggle-checkbox:checked + .toggle-label {
    @apply: bg-[#B91C1C];
}

.toggle-label {
    transition: background-color 0.3s ease;
}
</style>
@endsection