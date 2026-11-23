<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

class GuestAwareCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Nunca cacheia (nem serve cache) para convidado logado. O perfil padrão
     * do Spatie consulta o guard default (`web`), que o site não usa — todos
     * os convidados eram tratados como anônimos e compartilhavam a mesma
     * entrada de cache, servindo a página personalizada de um para os outros.
     */
    public function shouldCacheRequest(Request $request): bool
    {
        if (Auth::guard('guest')->check()) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }

    /**
     * Sufixo por convidado, caso alguma rota autenticada volte a ser cacheável.
     */
    public function useCacheNameSuffix(Request $request): string
    {
        if (Auth::guard('guest')->check()) {
            return 'guest-' . Auth::guard('guest')->id();
        }

        return parent::useCacheNameSuffix($request);
    }
}
