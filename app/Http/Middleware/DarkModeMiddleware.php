<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class DarkModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur a un cookie de préférence de thème
        $darkMode = $request->cookie('dark_mode', 'false');

        // Si c'est une requête AJAX pour changer le thème
        if ($request->isMethod('post') && $request->routeIs('theme.toggle')) {
            $darkMode = $request->input('dark_mode', 'false');

            // Retourner une réponse JSON pour AJAX
            return response()->json([
                'success' => true,
                'dark_mode' => $darkMode
            ])->cookie('dark_mode', $darkMode, 60 * 24 * 30); // 30 jours
        }

        // Partager la préférence avec toutes les vues
        view()->share('dark_mode', $darkMode === 'true');

        return $next($request);
    }
}
