<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class GiftCheckoutController extends Controller
{
    public function reserve(Request $request, Gift $gift)
    {
        $request->validate([
            'guest_name'  => ['required', 'string', 'max:255'],
            'guest_email' => ['nullable', 'email', 'max:255'],
            'message'     => ['nullable', 'string', 'max:2000'],
            'quantity'    => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            // 1. Cria o pedido em transação (sem chamadas externas)
            $order = DB::transaction(function () use ($request, $gift) {
                /** @var Gift $giftLocked */
                $giftLocked = Gift::query()
                    ->lockForUpdate()
                    ->findOrFail($gift->id);

                if (!$giftLocked->isActive()) {
                    abort(422, 'Este presente não está ativo.');
                }

                if ($giftLocked->isSold()) {
                    abort(422, 'Este presente já foi comprado.');
                }

                $quantity = null;
                $unitValue = (float) $giftLocked->valor;
                $totalValue = $unitValue;
                $reservationExpiresAt = now()->addMinutes(25);

                if ($giftLocked->isQuotaBased()) {
                    $quantity = (int) ($request->input('quantity', 1));

                    if ($quantity < 1) {
                        abort(422, 'Quantidade de cotas inválida.');
                    }

                    $remainingQuotas = $giftLocked->remainingQuotas();

                    if ($remainingQuotas < $quantity) {
                        abort(422, 'Não há cotas suficientes disponíveis.');
                    }

                    $unitValue = round((float) $giftLocked->valor / (int) $giftLocked->num_cotas, 2);
                    $totalValue = round($unitValue * $quantity, 2);
                } else {
                    if ($giftLocked->isReserved()) {
                        abort(422, 'Este presente está temporariamente reservado por outra pessoa.');
                    }

                    $giftLocked->reserveUntil($reservationExpiresAt);
                }

                return GiftOrder::create([
                    'gift_id'            => $giftLocked->id,
                    'guest_name'         => $request->guest_name,
                    'guest_email'        => $request->guest_email,
                    'message'            => $request->message,
                    'unit_value'         => $unitValue,
                    'quantity'           => $quantity,
                    'total_value'        => $totalValue,
                    'status'             => 'P',
                    'external_reference' => (string) Str::uuid(),
                    'reserved_until'     => $reservationExpiresAt,
                ]);
            });

            try {
                // 2. Cria preferência no Mercado Pago (fora da transaction)
                $checkoutUrl = $this->createMpPreference($order);
            } catch (\Throwable $e) {
                $this->rollbackPendingOrder($order);

                $apiResponse = null;
                if ($e instanceof \MercadoPago\Exceptions\MPApiException) {
                    $apiResponse = [
                        'status'  => $e->getApiResponse()?->getStatusCode(),
                        'body'    => $e->getApiResponse()?->getContent(),
                    ];
                }

                Log::error('MercadoPago checkout: falha ao criar preference', [
                    'order_id'            => $order->id,
                    'gift_id'             => $order->gift_id,
                    'external_reference'  => $order->external_reference,
                    'error'               => $e->getMessage(),
                    'api_response'        => $apiResponse,
                    'notification_url'    => url('/api/mercadopago/webhook'),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível iniciar o pagamento agora. Tente novamente em instantes.',
                    'error'   => config('app.debug') ? $e->getMessage() : null,
                ], 502);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pedido criado. Redirecionando para o pagamento...',
                'data' => [
                    'order_id'           => $order->id,
                    'gift_id'            => $order->gift_id,
                    'guest_name'         => $order->guest_name,
                    'guest_email'        => $order->guest_email,
                    'quantity'           => $order->quantity,
                    'unit_value'         => $order->unit_value,
                    'total_value'        => $order->total_value,
                    'external_reference' => $order->external_reference,
                    'reserved_until'     => optional($order->reserved_until)->format('Y-m-d H:i:s'),
                    'checkout_url'       => $checkoutUrl,
                ],
            ]);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Erro na reserva.',
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível reservar o presente agora.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function createMpPreference(GiftOrder $order): string
    {
        $accessToken = config('services.mercadopago.access_token');

        if (!$accessToken) {
            throw new \RuntimeException('Mercado Pago access token não configurado.');
        }

        MercadoPagoConfig::setAccessToken($accessToken);

        $order->loadMissing('gift');
        $gift = $order->gift;

        if (!$gift) {
            throw new \RuntimeException('Presente vinculado ao pedido não encontrado.');
        }

        $client = new PreferenceClient();

        $preference = $client->create([
            'items' => [[
                'title'      => $gift->nome,
                'quantity'   => $order->quantity ?? 1,
                'unit_price' => (float) $order->unit_value,
                'currency_id' => 'BRL',
            ]],
            'payer' => [
                'name'  => $order->guest_name,
                'email' => $order->guest_email ?: 'convidado@leticiaebrendon.com',
            ],
            'back_urls' => [
                'success' => route('gift-payment.success'),
                'failure' => route('gift-payment.failure'),
                'pending' => route('gift-payment.pending'),
            ],
            'auto_return'        => 'approved',
            'external_reference' => $order->external_reference,
            'notification_url'   => url('/api/mercadopago/webhook') . '?source_news=webhooks',
            'expires'            => true,
            'expiration_date_to' => $order->reserved_until->toIso8601String(),
        ]);

        $checkoutUrl = app()->environment('production')
            ? ($preference->init_point ?? null)
            : ($preference->sandbox_init_point ?? $preference->init_point ?? null);

        if (!$checkoutUrl) {
            throw new \RuntimeException('Mercado Pago não retornou uma URL de checkout válida.');
        }

        $order->update(['mp_preference_id' => $preference->id]);

        return $checkoutUrl;
    }

    private function rollbackPendingOrder(GiftOrder $order): void
    {
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

            if ($gift && !$gift->isQuotaBased()) {
                /** @var Gift|null $giftLocked */
                $giftLocked = Gift::query()
                    ->lockForUpdate()
                    ->find($gift->id);

                if ($giftLocked && $giftLocked->reservado === 'S') {
                    $giftLocked->clearReservation();
                }
            }

            $orderLocked->delete();
        });
    }
}
