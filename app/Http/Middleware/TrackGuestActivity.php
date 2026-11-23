<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackGuestActivity
{
    /**
     * Registra o último acesso do convidado logado a cada visita de página.
     *
     * Usa o query builder (DB::table) de propósito: grava apenas o
     * ultimo_acesso, sem tocar no updated_at — que representa a última
     * alteração de dados do convidado (presença, calçado, van).
     *
     * Precisa rodar ANTES do CacheResponse no grupo `web`, senão páginas
     * servidas do cache (home, informações) não registrariam o acesso.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guest = Auth::guard('guest')->user();

        if ($guest) {
            DB::table('guests')
                ->where('id', $guest->id)
                ->update(['ultimo_acesso' => now()]);
        }

        return $next($request);
    }
}
