<?php

namespace App\Filament\Widgets;

use App\Models\GiftOrder;
use App\Models\Guest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $confirmados = Guest::where('presenca_confirmada', 'S')->count();
        $pendentes = Guest::where('presenca_confirmada', 'N')->count();
        $arrecadado = (float) GiftOrder::where('status', 'A')->sum('total_value');
        $pedidosPagos = GiftOrder::where('status', 'A')->count();

        return [
            Stat::make('Presenças confirmadas', $confirmados)
                ->description($pendentes . ' pendente(s)')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),
            Stat::make('Total arrecadado', 'R$ ' . number_format($arrecadado, 2, ',', '.'))
                ->description($pedidosPagos . ' pedido(s) pago(s)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
            Stat::make('Total de convidados', Guest::count())
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
