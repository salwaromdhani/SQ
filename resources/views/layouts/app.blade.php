<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', "SmartQueue - Gestion de Files d'Attente")</title>

    @vite('resources/css/app.css')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

<div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl shadow-sm">

        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-3 text-lg font-semibold text-slate-900 transition hover:text-[#B91C1C]">

                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo"
                     class="h-11 w-11 rounded-2xl object-cover shadow-lg shadow-[#B91C1C]/20" />

                <span>File d'Attente</span>
            </a>

            <!-- Desktop menu -->
            <nav class="hidden items-center gap-4 md:flex">

                @if(Route::has('home'))
                    <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                @endif

                @if(Route::has('about'))
                    <a href="{{ route('about') }}" class="nav-link">À propos</a>
                @endif

                @if(Route::has('contact'))
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                @endif

                @auth
                    @if(auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>

                    @elseif(auth()->user() && method_exists(auth()->user(), 'isEmployee') && auth()->user()->isEmployee())
                        <a href="{{ route('employee.dashboard') }}" class="nav-link">Employé</a>

                    @else
                        <a href="{{ route('client.dashboard') }}" class="nav-link">Mon Espace</a>
                    @endif

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

            <!-- Right buttons -->
            <div class="flex items-center gap-3">

                <!-- Theme -->
                <button id="theme-toggle"
                        class="rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm hover:bg-slate-100">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>

                <!-- Mobile -->
                <button id="mobile-menu-toggle"
                        class="rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm md:hidden">
                    <i class="fas fa-bars"></i>
                </button>

            </div>

        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden border-t border-slate-200 bg-white px-4 pb-5 md:hidden">

            <div class="space-y-2 pt-4">

                @if(Route::has('home'))
                    <a href="{{ route('home') }}" class="block p-3 rounded-xl hover:bg-slate-100">Accueil</a>
                @endif

                @if(Route::has('about'))
                    <a href="{{ route('about') }}" class="block p-3 rounded-xl hover:bg-slate-100">À propos</a>
                @endif

                @if(Route::has('contact'))
                    <a href="{{ route('contact') }}" class="block p-3 rounded-xl hover:bg-slate-100">Contact</a>
                @endif

                @auth
                    <a href="#" class="block p-3 rounded-xl hover:bg-slate-100">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left p-3 rounded-xl hover:bg-slate-100">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block p-3 rounded-xl hover:bg-slate-100">
                        Connexion
                    </a>

                    <a href="{{ route('client.tickets.create') }}"
                       class="block p-3 rounded-xl bg-[#B91C1C] text-white text-center">
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
    <footer class="border-t border-slate-200 bg-white py-8">

        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 lg:flex-row lg:justify-between">

            <div>
                <p class="font-semibold">SmartQueue</p>
                <p class="text-sm text-slate-500">
                    Système moderne de gestion de files d'attente.
                </p>
            </div>

            <div class="flex gap-4 text-sm text-slate-500">
                @if(Route::has('about'))
                    <a href="{{ route('about') }}" class="hover:text-[#B91C1C]">À propos</a>
                @endif

                @if(Route::has('contact'))
                    <a href="{{ route('contact') }}" class="hover:text-[#B91C1C]">Contact</a>
                @endif
            </div>

        </div>

    </footer>

</div>

@vite('resources/js/app.js')

<!-- JS -->
<script>
const html = document.documentElement;
const toggle = document.getElementById('theme-toggle');
const icon = document.getElementById('theme-icon');

const theme = localStorage.getItem('theme') || 'light';

if (theme === 'dark') {
    html.classList.add('dark');
    icon.classList.replace('fa-moon', 'fa-sun');
}

toggle?.addEventListener('click', () => {
    const isDark = html.classList.toggle('dark');

    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    icon.classList.toggle('fa-sun', isDark);
    icon.classList.toggle('fa-moon', !isDark);
});

const btn = document.getElementById('mobile-menu-toggle');
const menu = document.getElementById('mobile-menu');

btn?.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});
</script>

</body>
</html>