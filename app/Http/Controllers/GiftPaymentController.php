<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class GiftPaymentController extends Controller
{
    public function success(Request $request)
    {
        $order = $this->findOrder($request);
        $this->sharePageMeta('Pagamento confirmado');
        return view('site.pages.gift-payment', ['status' => 'success', 'order' => $order]);
    }

    public function failure(Request $request)
    {
        $order = $this->findOrder($request);

        $this->releaseReservationForFailedOrder($order);

        $this->sharePageMeta('Pagamento não concluído');
        return view('site.pages.gift-payment', ['status' => 'failure', 'order' => $order]);
    }

    public function pending(Request $request)
    {
        $order = $this->findOrder($request);
        $this->sharePageMeta('Pagamento pendente');
        return view('site.pages.gift-payment', ['status' => 'pending', 'order' => $order]);
    }

    private function findOrder(Request $request): ?GiftOrder
    {
        $externalRef = $request->query('external_reference');

        if (!$externalRef) {
            return null;
        }

        return GiftOrder::with('gift')
            ->where('external_reference', $externalRef)
            ->first();
    }

    private function sharePageMeta(string $title): void
    {
        $site = new \stdClass();
        $site->meta = new \stdClass();
        $site->meta->title       = $title . ' - Letícia e Brendon';
        $site->meta->description = 'Lista de presentes do casamento de Letícia e Brendon.';
        $site->meta->keywords    = 'lista de presentes, casamento, letícia, brendon';
        View::share('site', $site);
    }

    private function releaseReservationForFailedOrder(?GiftOrder $order): void
    {
        if (!$order || !$order->isPending()) {
            return;
        }

        DB::transaction(function () use ($order) {
            /** @var GiftOrder|null $orderLocked */
            $orderLocked = GiftOrder::query()
                ->lockForUpdate()
                ->with('gift')
                ->find($order->id);

            if (!$orderLocked || !$orderLocked->isPending()) {
                return;
            }

            $gift = $orderLocked->gift;

            if (!$gift || $gift->isQuotaBased()) {
                return;
            }

            /** @var Gift|null $giftLocked */
            $giftLocked = Gift::query()
                ->lockForUpdate()
                ->find($gift->id);

            if (!$giftLocked || $giftLocked->reservado !== 'S') {
                return;
            }

            if (
                $giftLocked->reservado_ate
                && $orderLocked->reserved_until
                && $giftLocked->reservado_ate->gt($orderLocked->reserved_until)
            ) {
                return;
            }

            $giftLocked->clearReservation();
        });
    }
}
