<?php

namespace App\Console\Commands;

use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredGiftReservations extends Command
{
    protected $signature = 'gifts:release-expired-reservations';
    protected $description = 'Expira pedidos pendentes e libera reservas vencidas da lista de presentes';

    public function handle(): int
    {
        $cutoff = now();

        $orderIds = GiftOrder::query()
            ->where('status', 'P')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<=', $cutoff)
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            $this->info('Nenhuma reserva expirada encontrada.');

            return self::SUCCESS;
        }

        $expiredOrders = 0;
        $releasedReservations = 0;

        foreach ($orderIds as $orderId) {
            $result = DB::transaction(function () use ($orderId, $cutoff) {
                /** @var GiftOrder|null $order */
                $order = GiftOrder::query()
                    ->lockForUpdate()
                    ->with('gift')
                    ->find($orderId);

                if (!$order || !$order->isPending() || !$order->reserved_until || $order->reserved_until->isFuture()) {
                    return [
                        'expired' => false,
                        'released' => false,
                    ];
                }

                $released = false;

                if ($order->gift && !$order->gift->isQuotaBased()) {
                    /** @var Gift|null $gift */
                    $gift = Gift::query()
                        ->lockForUpdate()
                        ->find($order->gift_id);

                    if ($gift && $gift->reservado === 'S' && $gift->reservado_ate && $gift->reservado_ate->lte($cutoff)) {
                        $gift->clearReservation();
                        $released = true;
                    }
                }

                $order->update([
                    'status' => 'E',
                ]);

                return [
                    'expired' => true,
                    'released' => $released,
                ];
            });

            if ($result['expired']) {
                $expiredOrders++;
            }

            if ($result['released']) {
                $releasedReservations++;
            }
        }

        $this->info("Pedidos expirados: {$expiredOrders}");
        $this->info("Reservas liberadas: {$releasedReservations}");

        return self::SUCCESS;
    }
}
