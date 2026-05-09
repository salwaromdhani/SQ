@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white/90 p-8 shadow-[0_24px_90px_-45px_rgba(15,23,42,0.12)]">
        <h2 class="text-3xl font-semibold text-slate-900">Contactez SmartQueue</h2>
        <p class="mt-3 text-slate-600">Pour toute question technique ou suggestion relative à l'application SmartQueue, contactez notre équipe dédiée.</p>
        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6 text-slate-700">
            <p class="text-sm font-semibold text-slate-900">Email :</p>
            <p>contact@smartqueue.com</p>
            <p class="mt-4 text-sm font-semibold text-slate-900">Téléphone :</p>
            <p>+216 25 93 80 87</p>
        </div>
    </div>
</div>
@endsection