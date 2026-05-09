@extends('layouts.app')

@section('title', 'Connexion Admin')

@section('content')
<div class="min-h-[calc(100vh-6rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md rounded-[2rem] border border-white/10 bg-slate-950/95 p-8 shadow-[0_35px_80px_-45px_rgba(15,23,42,0.95)] backdrop-blur-xl">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-5 inline-flex h-16 w-16 items-center justify-center rounded-full bg-rose-500/15 text-rose-300 shadow-sm">
                <i class="fas fa-user-lock text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white">Connexion administrateur</h2>
            <p class="mt-3 text-sm text-slate-400">Entrez vos identifiants pour accéder à l’espace admin sécurisé.</p>
        </div>
        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <label for="email" class="block text-sm font-semibold text-white/80">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="block w-full rounded-3xl border border-slate-700 bg-slate-950 px-4 py-4 text-white shadow-sm transition focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="space-y-4">
                <label for="password" class="block text-sm font-semibold text-white/80">Mot de passe</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="block w-full rounded-3xl border border-slate-700 bg-slate-950 px-4 py-4 text-white shadow-sm transition focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    placeholder="Mot de passe">
            </div>

            @if($errors->any())
                <div class="rounded-3xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="btn-primary w-full text-center">Se connecter</button>
        </form>
    </div>
</div>
@endsection