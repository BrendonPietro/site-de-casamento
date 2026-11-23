<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use App\Models\Gift;
use stdClass;

class HomeController extends Controller
{
    public function index()
    {
        $site = new stdClass();
        $site->meta = new stdClass();
        $site->meta->title       = "Letícia e Brendon";
        $site->meta->description = "Acompanhe nosso grande dia! Confira os detalhes do casamento de Letícia e Brendon.";
        $site->meta->keywords    = "casamento, letícia, brendon";

        View::share('site', $site);
        return view('site.pages.home');
    }

    public function informacoes()
    {
        $site = new stdClass();
        $site->meta = new stdClass();
        $site->meta->title       = "Informações - Letícia e Brendon";
        $site->meta->description = "Confira todas as informações sobre o casamento: cerimônia, recepção, dress code e mais.";
        $site->meta->keywords    = "informações, casamento, cerimônia, recepção, dress code";

        View::share('site', $site);
        return view('site.pages.informacoes');
    }

    public function giftList()
    {
        $site = new stdClass();
        $site->meta = new stdClass();
        $site->meta->title       = "Lista de Presentes - Letícia e Brendon";
        $site->meta->description = "Confira a lista de presentes do casamento de Letícia e Brendon.";
        $site->meta->keywords    = "lista de presentes, casamento, letícia, brendon";

        View::share('site', $site);

        $gifts = Gift::where('ativo', 'S')->get();
        $categorias = Gift::where('ativo', 'S')->whereNotNull('categoria')->distinct()->pluck('categoria')->sort()->values();

        return view('site.pages.gift-list', compact('gifts', 'categorias'));
    }
}
