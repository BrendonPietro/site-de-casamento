<?php

namespace App\Console\Commands;

use App\Models\Artista;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Facades\ResponseCache;

class CachePages extends Command
{
    protected $signature = 'cache:pages';
    protected $description = 'Generate cache for all defined pages';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ResponseCache::clear();

        // Definir as rotas que você quer cachear
        $routes = $this->getRoutes();

        foreach ($routes as $route) {

            if( strpos($route, 'cache/') !== false )
                continue;

            if (strpos($route, '.test') !== false) {
                $route = str_replace('localhost/', '', $route);
            }

            if (strpos($route, '/whatsapp') !== false || strpos($route, '/telefone') !== false || strpos($route, '/email') !== false) {
                continue;
                // dd($route);
            } elseif (strpos($route, '/artista/') !== false) {
                $artista = Artista::first();

                if ($artista) {
                    $route = str_replace('{slug}', $artista->str_slug, $route);
                }
            }

            // Fazer a requisição à rota
            $response = Http::get(str_replace(url('/'), 'http://127.0.0.1:8000', $route));

            if ($response->successful()) {
                $this->info("Cache gerado com sucesso para: $route");
            } else {
                $this->error("Falha ao gerar cache para: $route (Status: " . $response->status() . ")");
            }

            $this->info("Cache gerado para: $route");
        }

        $this->info('Cache de todas as páginas gerado com sucesso!');
    }

    private function getRoutes()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            // Verificar se a rota pertence ao grupo 'web', usa o método GET, e não é redirecionamento
            return in_array('GET', $route->methods()) &&
                in_array('web', $route->gatherMiddleware()) &&
                strpos($route->uri(), 'sanctum/csrf-cookie') === false &&
                !$this->isRedirectRoute($route);
        })->map(function ($route) {
            // Gerar a URL completa da rota
            return url($route->uri());
        });

        return $routes;
    }

    protected function isRedirectRoute($route)
    {
        $action = $route->getAction();

        // Verificar se a rota é uma Closure que contém um redirect
        if ($action['uses'] instanceof \Closure) {
            $reflection = new \ReflectionFunction($action['uses']);
            $lines = file($reflection->getFileName());
            $code = implode("", array_slice($lines, $reflection->getStartLine() - 1, $reflection->getEndLine() - $reflection->getStartLine() + 1));

            // Verificar se o código contém o método `redirect()`
            return strpos($code, 'redirect(') !== false;
        }

        // Verificar se a rota usa o método redirect diretamente
        return isset($action['controller']) && strpos($action['controller'], '@redirect') !== false;
    }
}
