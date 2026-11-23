<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class GlobalConfig
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
        $globalConfig = new stdClass();
        $globalConfig->title            = 'Casamento Letícia e Brendon';
        $globalConfig->email            = 'casamento@leticiaebrendon.com';
        $globalConfig->whatsapp         = '(11) 90000-0000';
        $globalConfig->whatsapp_link    = 'https://api.whatsapp.com/send?phone=55' . preg_replace('/\D/', '', $globalConfig->whatsapp);

        View::share('globalConfig', $globalConfig);

        // Guest auth state shared globally
        $guestUser = Auth::guard('guest')->user();
        View::share('guestLogado', $guestUser);
        View::share('guestTipo', $guestUser?->tipo);

		return $next($request);
	}
}
