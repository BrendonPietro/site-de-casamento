<?php

namespace Tests\Feature;

use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MercadoPagoWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.mercadopago.webhook_secret' => null]);
        config(['services.mercadopago.access_token' => null]);
    }

    public function test_webhook_returns_ok_for_non_payment_events(): void
    {
        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'merchant_order',
            'data' => ['id' => '123'],
        ])->assertOk()->assertJson(['ok' => true]);
    }

    public function test_webhook_returns_ok_when_payment_id_is_missing(): void
    {
        $this->postJson('/api/mercadopago/webhook', [])
            ->assertOk();
    }

    public function test_webhook_ignores_ipn_non_payment_topic(): void
    {
        $this->postJson('/api/mercadopago/webhook?topic=merchant_order&id=123', [])
            ->assertOk();
    }

    public function test_webhook_rejects_request_with_invalid_signature(): void
    {
        config(['services.mercadopago.webhook_secret' => 'my-secret']);

        $this->postJson(
            '/api/mercadopago/webhook',
            ['type' => 'payment', 'data' => ['id' => '12345']],
            ['x-signature' => 'ts=111,v1=invalidsignature'],
        )->assertStatus(401);
    }

    public function test_webhook_rejects_request_without_signature_when_secret_configured(): void
    {
        config(['services.mercadopago.webhook_secret' => 'my-secret']);

        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '12345'],
        ])->assertStatus(401);
    }

    public function test_webhook_passes_when_no_secret_configured(): void
    {
        // Sem secret → pula verificação → tenta buscar pagamento no MP → falha, mas retorna 200
        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '12345'],
        ])->assertOk(); // erros internos retornam 200 para o MP não retentar
    }

    public function test_webhook_accepts_valid_hmac_signature(): void
    {
        $secret    = 'test-secret-key';
        $paymentId = '99999';
        $requestId = 'req-abc123';
        $ts        = (string) time();

        config(['services.mercadopago.webhook_secret' => $secret]);

        $template  = "id:{$paymentId};request-id:{$requestId};ts:{$ts};";
        $signature = hash_hmac('sha256', $template, $secret);

        // Passa a assinatura, falha na busca do pagamento no MP (sem token), mas retorna 200
        $this->postJson(
            "/api/mercadopago/webhook?data_id={$paymentId}",
            ['type' => 'payment', 'data' => ['id' => $paymentId]],
            [
                'x-signature'  => "ts={$ts},v1={$signature}",
                'x-request-id' => $requestId,
            ],
        )->assertOk();
    }

    public function test_webhook_returns_ok_for_unknown_payment_order(): void
    {
        // Sem secret, simulação de payment com external_reference que não existe
        // O webhook deve retornar 200 (sem crash) mesmo não encontrando o pedido
        config(['services.mercadopago.access_token' => 'fake-token-for-test']);

        // Sem mock do PaymentClient o teste vai falhar na chamada HTTP real ao MP
        // → capturado no catch do handle() e retorna 200
        $this->postJson('/api/mercadopago/webhook', [
            'type' => 'payment',
            'data' => ['id' => '00000001'],
        ])->assertOk();
    }

    public function test_gift_order_fields_after_approval_via_direct_model_update(): void
    {
        // Testa a lógica de aprovação diretamente nos models (sem chamada MP)
        $gift  = Gift::factory()->create(['vendido' => 'N', 'reservado' => 'S', 'reservado_ate' => now()->addMinutes(20)]);
        $order = GiftOrder::factory()->forGift($gift)->create([
            'status'       => 'P',
            'mp_status'    => 'pending',
            'reserved_until' => now()->addMinutes(20),
        ]);

        // Simula o que o webhook faria ao aprovar
        $order->update([
            'mp_payment_id'    => '12345678',
            'mp_status'        => 'approved',
            'mp_status_detail' => 'accredited',
            'payment_method'   => 'credit_card',
            'status'           => 'A',
            'paid_at'          => now(),
            'reserved_until'   => null,
        ]);

        $gift->update(['vendido' => 'S', 'reservado' => 'N', 'reservado_ate' => null]);

        $order->refresh();
        $gift->refresh();

        $this->assertEquals('A', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertEquals('S', $gift->vendido);
        $this->assertEquals('N', $gift->reservado);
        $this->assertTrue($order->isApproved());
    }
}
