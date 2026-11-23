<?php

namespace Tests\Feature;

use App\Models\Gift;
use App\Models\GiftOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // No MP token → MP call fails predictably (502), but DB logic runs first
        config(['services.mercadopago.access_token' => null]);
    }

    public function test_reserve_requires_guest_name(): void
    {
        $gift = Gift::factory()->create();

        $this->postJson("/api/gifts/{$gift->id}/reserve", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('guest_name');
    }

    public function test_reserve_rejects_invalid_email(): void
    {
        $gift = Gift::factory()->create();

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name'  => 'João Silva',
            'guest_email' => 'not-an-email',
        ])->assertUnprocessable()
          ->assertJsonValidationErrorFor('guest_email');
    }

    public function test_cannot_reserve_sold_gift(): void
    {
        $gift = Gift::factory()->sold()->create();

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
        ])->assertStatus(422)
          ->assertJsonFragment(['success' => false]);
    }

    public function test_cannot_reserve_already_reserved_gift(): void
    {
        $gift = Gift::factory()->reserved(now()->addHour())->create();

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
        ])->assertStatus(422)
          ->assertJsonFragment(['success' => false]);
    }

    public function test_cannot_reserve_inactive_gift(): void
    {
        $gift = Gift::factory()->inactive()->create();

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
        ])->assertStatus(422)
          ->assertJsonFragment(['success' => false]);
    }

    public function test_cannot_reserve_fully_sold_quota_gift(): void
    {
        $gift = Gift::factory()->quotaBased(3)->create(['cotas_vendidas' => 3]);

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
            'quantity'   => 1,
        ])->assertStatus(422)
          ->assertJsonFragment(['success' => false]);
    }

    public function test_cannot_request_more_quotas_than_available(): void
    {
        $gift = Gift::factory()->quotaBased(5)->create(['cotas_vendidas' => 3]);

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
            'quantity'   => 5, // apenas 2 disponíveis
        ])->assertStatus(422)
          ->assertJsonFragment(['success' => false]);
    }

    public function test_reserve_rollbacks_order_and_clears_reservation_when_mp_fails(): void
    {
        $gift = Gift::factory()->create(['valor' => '200.00']);

        // Sem token MP → 502, mas a lógica de rollback deve limpar a reserva e o pedido
        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name'  => 'Maria Silva',
            'guest_email' => 'maria@email.com',
        ])->assertStatus(502)
          ->assertJsonFragment(['success' => false]);

        $this->assertDatabaseMissing('gift_orders', ['guest_name' => 'Maria Silva']);

        $gift->refresh();
        $this->assertEquals('N', $gift->reservado);
    }

    public function test_quota_gift_reserve_rollbacks_without_reservation(): void
    {
        // Presentes cotáveis não são reservados individualmente
        $gift = Gift::factory()->quotaBased(10)->create(['valor' => '500.00']);

        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'Carlos',
            'quantity'   => 2,
        ])->assertStatus(502);

        $this->assertDatabaseMissing('gift_orders', ['guest_name' => 'Carlos']);

        // Quota gift não deve ser marcado como reservado
        $gift->refresh();
        $this->assertEquals('N', $gift->reservado);
    }

    public function test_reserve_returns_404_for_nonexistent_gift(): void
    {
        $this->postJson('/api/gifts/99999/reserve', [
            'guest_name' => 'João',
        ])->assertNotFound();
    }

    public function test_expired_reservation_can_be_reserved_again(): void
    {
        $gift = Gift::factory()->reserved(now()->subMinute())->create();

        // Reserva expirada → presente disponível → deve criar pedido (falha só no MP)
        $this->postJson("/api/gifts/{$gift->id}/reserve", [
            'guest_name' => 'João Silva',
        ])->assertStatus(502); // falha no MP, não na validação

        // O rollback limpa a nova reserva
        $gift->refresh();
        $this->assertEquals('N', $gift->reservado);
    }
}
