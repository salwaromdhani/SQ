@extends('layouts.app')

@section('title', 'Accueil - SmartQueue')

@section('content')
<div class="space-y-16">
    <section class="hero-panel">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-10 lg:grid-cols-[0.95fr_0.8fr] lg:items-center">
                <div class="space-y-6">
                    <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-semibold uppercase tracking-[0.3em] text-slate-200">Virtual Queue</span>
                    <h1 class="text-5xl font-semibold tracking-tight sm:text-6xl">Gérez vos opérations bancaires en ligne avec un système de file d'attente moderne.</h1>
                    <p class="max-w-2xl text-lg text-slate-200/90">Prenez un ticket virtuel, suivez votre position en temps réel et recevez des notifications automatiques lorsque votre tour arrive.</p>
                    <div class="flex flex-col gap-4 sm:flex-row sm:flex-wrap">
                        <a href="{{ route('client.tickets.create') }}" class="btn-primary inline-flex items-center gap-2">
                            <i class="fas fa-plus-circle text-base"></i>
                            Prendre un ticket
                        </a>
                        <a href="{{ route('client.queue.live') }}" class="btn-secondary inline-flex items-center gap-2">
                            <i class="fas fa-list text-base"></i>
                            Voir la file d'attente
                        </a>
                    </div>
                </div>
                <div class="rounded-[2rem] border border-white/10 bg-white/10 p-8 shadow-[0_30px_80px_-50px_rgba(0,0,0,0.35)] backdrop-blur-xl">
                    <div class="mb-6 flex items-center gap-4 rounded-3xl bg-slate-950/80 p-5">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-indigo-500 text-white shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-300">Stats en direct</p>
                            <p class="text-2xl font-semibold text-white">{{ $stats['total'] ?? 0 }} tickets gérés aujourd'hui</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-3 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                            <p class="text-sm text-slate-300">En attente</p>
                            <p class="text-3xl font-semibold text-white">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                        <div class="space-y-3 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                            <p class="text-sm text-slate-300">En cours</p>
                            <p class="text-3xl font-semibold text-white">{{ $stats['serving'] ?? 0 }}</p>
                        </div>
                        <div class="space-y-3 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                            <p class="text-sm text-slate-300">Temps moyen</p>
                            <p class="text-3xl font-semibold text-white">{{ $stats['average_wait'] ?? 0 }} min</p>
                        </div>
                        <div class="space-y-3 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                            <p class="text-sm text-slate-300">Notifications</p>
                            <p class="text-3xl font-semibold text-white">Automatiques</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="feature-card">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-slate-900">Prise de ticket rapide</h2>
            <p class="mt-3 text-slate-600">Un formulaire simplifié pour obtenir votre numéro de file en quelques secondes.</p>
        </div>
        <div class="feature-card">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-50 text-sky-600">
                <i class="fas fa-bell text-xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-slate-900">Notifications SMS/Email</h2>
            <p class="mt-3 text-slate-600">Recevez une alerte lorsque votre tour approche pour ne pas manquer votre passage.</p>
        </div>
        <div class="feature-card">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-emerald-50 text-emerald-600">
                <i class="fas fa-chart-pie text-xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-slate-900">Suivi temps réel</h2>
            <p class="mt-3 text-slate-600">Visualisez votre position dans la file et votre temps d'attente estimé.</p>
        </div>
    </section>
</div>
@endsection
