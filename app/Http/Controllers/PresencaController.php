<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guest;
use Illuminate\Support\Facades\View;

class PresencaController extends Controller
{
    public function index()
    {
        /** @var Guest $guest */
        $guest = Auth::guard('guest')->user();
        $dependentes = $guest->dependentes()->orderBy('nome_completo')->get();

        $site = new \stdClass();
        $site->meta = new \stdClass();
        $site->meta->title       = "Confirmar Presença - Letícia e Brendon";
        $site->meta->description = "Confirme sua presença no casamento de Letícia e Brendon.";
        $site->meta->keywords    = "confirmar presença, casamento, letícia, brendon";

        View::share('site', $site);

        return view('site.pages.confirmar-presenca', compact('guest', 'dependentes'));
    }

    public function confirmar(Request $request)
    {
        /** @var Guest $guest */
        $guest = Auth::guard('guest')->user();
        $dependentesIds = $guest->dependentes()->pluck('guests.id')->toArray();

        $confirmados = array_map('intval', $request->input('confirmados', []));
        $calcados    = $request->input('calcado', []);

        // Atualiza o próprio guest
        $guest->presenca_confirmada = in_array($guest->id, $confirmados) ? 'S' : 'N';
        if ($guest->sexo === 'F' && isset($calcados[$guest->id])) {
            $guest->numero_calcado = (int) $calcados[$guest->id];
        }
        // Sempre registra a alteração, mesmo quando o valor não muda.
        $guest->updated_at = now();
        $guest->save();

        // Atualiza apenas os dependentes autorizados
        foreach ($dependentesIds as $depId) {
            $dep = Guest::find($depId);
            if (!$dep) continue;

            $updates = [
                'presenca_confirmada' => in_array($depId, $confirmados) ? 'S' : 'N',
                'updated_at' => now(),
            ];

            if ($dep->sexo === 'F' && isset($calcados[$depId])) {
                $updates['numero_calcado'] = (int) $calcados[$depId];
            }

            Guest::where('id', $depId)->update($updates);
        }

        return redirect()->route('confirmar-presenca')->with('presenca_salva', true);
    }

    public function confirmarVan(Request $request)
    {
        /** @var Guest $guest */
        $guest = Auth::guard('guest')->user();
        $dependentesIds = $guest->dependentes()->pluck('guests.id')->toArray();

        $vanIds = array_map('intval', $request->input('van_ids', []));

        // Atualiza o próprio guest
        $guest->interesse_van = in_array($guest->id, $vanIds) ? 'S' : 'N';
        // Sempre registra a alteração, mesmo quando o valor não muda.
        $guest->updated_at = now();
        $guest->save();

        // Atualiza apenas os dependentes autorizados
        foreach ($dependentesIds as $depId) {
            Guest::where('id', $depId)->update([
                'interesse_van' => in_array($depId, $vanIds) ? 'S' : 'N',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('confirmar-presenca')->with('van_salva', true);
    }
}
