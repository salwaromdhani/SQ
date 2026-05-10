<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', "SmartQueue - Gestion de Files d'Attente")</title>

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-pY1Y9V8RueXzC0aQk8dmDDUzn3vDQ5oXqpk5ffiJeao5c2xO1Dq9U0hkBSEmXImGfM4XLs8Yg4jOJx5yVhDByQ=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

<div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl shadow-sm">

        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-3 text-lg font-semibold text-slate-900 transition hover:text-indigo-600">

                <img src="{{ asset('images/logo.png') }}"
                     alt="File d'Attente"
                     class="h-11 w-11 rounded-2xl object-cover shadow-lg shadow-indigo-500/20" />

                <span>File d'Attente</span>
            </a>

            <!-- Desktop menu -->
            <nav class="hidden items-center gap-4 md:flex">

                <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                <a href="{{ route('about') }}" class="nav-link">À propos</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="nav-link">Déconnexion</button>
                    </form>
                @else
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="nav-link">Connexion Admin</a>
                    @endif

                    <a href="{{ route('client.tickets.create') }}" class="btn-primary">
                        Créer un ticket
                    </a>
                @endauth

            </nav>

            <!-- Mobile button -->
            <button id="mobile-menu-toggle"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-700 shadow-sm transition hover:bg-slate-100 md:hidden">
                <span class="sr-only">Ouvrir le menu</span>
                <i class="fas fa-bars"></i>
            </button>

        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden border-t border-slate-200 bg-white/95 px-4 pb-5 shadow-sm md:hidden">

            <div class="space-y-3 pt-4">

                <a href="{{ route('home') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-100">Accueil</a>
                <a href="{{ route('about') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-100">À propos</a>
                <a href="{{ route('contact') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-100">Contact</a>

                @auth
                    <a href="{{ route('admin.tickets.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-100">
                        Admin
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl px-4 py-3 text-left hover:bg-slate-100">
                            Déconnexion
                        </button>
                    </form>
                @else
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-100">
                            Connexion Admin
                        </a>
                    @endif

                    <a href="{{ route('client.tickets.create') }}"
                       class="block rounded-2xl bg-indigo-600 px-4 py-3 text-center text-white hover:bg-indigo-700">
                        Créer un ticket
                    </a>
                @endauth

            </div>
        </div>

    </header>

    <!-- MAIN -->
    <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-slate-200/80 bg-white/95 py-8">

        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">

            <div>
                <p class="text-sm font-semibold text-slate-900">SmartQueue</p>
                <p class="mt-2 max-w-xl text-sm text-slate-500">
                    Système moderne de gestion de files d'attente bancaires avec notifications temps réel.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 text-sm text-slate-500">
                <a href="{{ route('about') }}" class="hover:text-indigo-600">À propos</a>
                <span>•</span>
                <a href="{{ route('contact') }}" class="hover:text-indigo-600">Contact</a>
            </div>

        </div>

    </footer>

</div>

@vite('resources/js/app.js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>

</body>
</html>