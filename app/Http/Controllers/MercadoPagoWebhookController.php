<?php

namespace App\Http\Controllers;

use App\Mail\GiftPurchasedMail;
use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoWebhookController extends Controller
{
    // Mapeamento status MP → status interno
    private const STATUS_MAP = [
        'approved'    => 'A',
        'rejected'    => 'R',
        'cancelled'   => 'C',
        'refunded'    => 'C',
        'pending'     => 'P',
        'in_process'  => 'P',
        'authorized'  => 'P',
    ];

    public function handle(Request $request)
    {
        // Verificação de assinatura (quando MP_WEBHOOK_SECRET estiver configurado)
        if (config('services.mercadopago.webhook_secret')) {
            if (!$this->verifySignature($request)) {
                Log::warning('MercadoPago webhook: assinatura inválida', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        // Suporta formato webhook (JSON body) e IPN (query params)
        $paymentId = null;

        if ($request->input('type') === 'payment') {
            $paymentId = $request->input('data.id');
        } elseif ($request->query('topic') === 'payment') {
            $paymentId = $request->query('id');
        }

        if (!$paymentId) {
            // Outros tipos de notificação (merchant_order, etc.) — ignorar
            return response()->json(['ok' => true]);
        }

        try {
            $this->processPayment((int) $paymentId);
        } catch (\Throwable $e) {
            Log::error('MercadoPago webhook: erro ao processar pagamento', [
                'payment_id' => $paymentId,
                'error'      => $e->getMessage(),
            ]);

            // Retorna 200 para o MP não retentar indefinidamente em erros de lógica
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => true]);
    }

    private function processPayment(int $paymentId): void
    {
        $accessToken = config('services.mercadopago.access_token');

        if (!$accessToken) {
            throw new \RuntimeException('Mercado Pago access token não configurado.');
        }

        MercadoPagoConfig::setAccessToken($accessToken);

        $paymentClient = new PaymentClient();
        $payment = $paymentClient->get($paymentId);

        if (!$payment || !$payment->external_reference) {
            return;
        }

        $newStatus = self::STATUS_MAP[$payment->status] ?? 'P';

        DB::transaction(function () use ($payment, $paymentId, $newStatus) {
            /** @var GiftOrder|null $order */
            $order = GiftOrder::query()
                ->lockForUpdate()
                ->where('external_reference', $payment->external_reference)
                ->first();

            if (!$order) {
                Log::warning('MercadoPago webhook: pedido não encontrado', [
                    'external_reference' => $payment->external_reference,
                    'payment_id'         => $paymentId,
                ]);
                return;
            }

            $isAlreadyProcessedPayment = $order->mp_payment_id === (string) $payment->id
                && $order->mp_status === $payment->status
                && $order->mp_status_detail === $payment->status_detail;

            if ($isAlreadyProcessedPayment) {
                return;
            }

            $wasApproved = $order->status === 'A' && $order->paid_at !== null;
            $isNewApproval = $newStatus === 'A' && !$wasApproved;
            $isNewFailure = in_array($newStatus, ['R', 'C'], true) && !$wasApproved;

            $order->update([
                'mp_payment_id'    => (string) $payment->id,
                'mp_status'        => $payment->status,
                'mp_status_detail' => $payment->status_detail,
                'payment_method'   => $payment->payment_method_id,
                'status'           => $newStatus,
                'paid_at'          => $newStatus === 'A' ? now() : $order->paid_at,
                'reserved_until'   => in_array($newStatus, ['A', 'R', 'C'], true) ? null : $order->reserved_until,
            ]);

            if ($isNewApproval) {
                $this->handleApproval($order);
            }

            if ($isNewFailure) {
                $this->handleFailure($order);
            }
        });
    }

    private function handleApproval(GiftOrder $order): void
    {
        /** @var Gift $gift */
        $gift = Gift::lockForUpdate()->find($order->gift_id);

        if (!$gift) {
            return;
        }

        if ($gift->isQuotaBased()) {
            $gift->increment('cotas_vendidas', $order->quantity ?? 1);
            // Marca como vendido se todas as cotas foram preenchidas
            if ($gift->fresh()->remainingQuotas() <= 0) {
                $gift->update(['vendido' => 'S']);
            }
        } else {
            $gift->update([
                'vendido'       => 'S',
                'reservado'     => 'N',
                'reservado_ate' => null,
            ]);
        }

        // Envia email de agradecimento se tiver endereço
        if ($order->guest_email) {
            try {
                Mail::to($order->guest_email)->send(new GiftPurchasedMail($order));
            } catch (\Throwable $e) {
                Log::warning('Falha ao enviar email de agradecimento', [
                    'order_id' => $order->id,
                    'email'    => $order->guest_email,
                    'error'    => $e->getMessage(),
                ]);
            }
        }
    }

    private function handleFailure(GiftOrder $order): void
    {
        $gift = Gift::find($order->gift_id);

        if ($gift && !$gift->isQuotaBased()) {
            $gift->clearReservation();
        }
    }

    /**
     * Verifica a assinatura HMAC-SHA256 enviada pelo Mercado Pago.
     * Docs: https://www.mercadopago.com.br/developers/pt/docs/your-integrations/notifications/webhooks
     */
    private function verifySignature(Request $request): bool
    {
        $signatureHeader = $request->header('x-signature');
        $requestId       = $request->header('x-request-id');

        if (!$signatureHeader) {
            return false;
        }

        // Extrai ts e v1 do header: "ts=123456,v1=abcdef"
        $parts = [];
        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $value] = explode('=', $part, 2);
            $parts[trim($key)] = trim($value);
        }

        if (empty($parts['ts']) || empty($parts['v1'])) {
            return false;
        }

        // PHP converts dots in query-string keys to underscores, so ?data.id=X becomes data_id
        $dataId = (string) ($request->query('data_id') ?? $request->input('data.id', ''));

        if ($dataId !== '' && !is_numeric($dataId)) {
            $dataId = strtolower($dataId);
        }

        $templateParts = [];

        if ($dataId !== '') {
            $templateParts[] = "id:{$dataId}";
        }

        if ($requestId) {
            $templateParts[] = "request-id:{$requestId}";
        }

        $templateParts[] = "ts:{$parts['ts']}";

        $template = implode(';', $templateParts) . ';';

        $expectedHash = hash_hmac('sha256', $template, config('services.mercadopago.webhook_secret'));

        return hash_equals($expectedHash, $parts['v1']);
    }
}
