<?php

namespace Tests\Unit;

use App\Models\Gift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_unsold_unreserved_gift_is_available(): void
    {
        $gift = Gift::factory()->create();
        $this->assertTrue($gift->isAvailable());
    }

    public function test_sold_gift_is_not_available(): void
    {
        $gift = Gift::factory()->sold()->create();
        $this->assertFalse($gift->isAvailable());
        $this->assertTrue($gift->isSold());
    }

    public function test_inactive_gift_is_not_available(): void
    {
        $gift = Gift::factory()->inactive()->create();
        $this->assertFalse($gift->isAvailable());
    }

    public function test_reserved_gift_with_future_expiry_is_not_available(): void
    {
        $gift = Gift::factory()->reserved(now()->addHour())->create();
        $this->assertTrue($gift->isReserved());
        $this->assertFalse($gift->isAvailable());
    }

    public function test_reserved_gift_with_past_expiry_is_available(): void
    {
        $gift = Gift::factory()->reserved(now()->subMinute())->create();
        $this->assertFalse($gift->isReserved());
        $this->assertTrue($gift->isAvailable());
    }

    public function test_quota_gift_with_remaining_quotas_is_available(): void
    {
        $gift = Gift::factory()->quotaBased(5)->create(['cotas_vendidas' => 2]);
        $this->assertTrue($gift->isAvailable());
        $this->assertEquals(3, $gift->remainingQuotas());
    }

    public function test_quota_gift_fully_sold_is_not_available(): void
    {
        $gift = Gift::factory()->quotaBased(5)->create(['cotas_vendidas' => 5]);
        $this->assertFalse($gift->isAvailable());
        $this->assertEquals(0, $gift->remainingQuotas());
    }

    public function test_reserve_until_marks_gift_as_reserved(): void
    {
        $gift = Gift::factory()->create();
        $until = now()->addMinutes(30);
        $gift->reserveUntil($until);

        $gift->refresh();
        $this->assertEquals('S', $gift->reservado);
        $this->assertTrue($gift->isReserved());
    }

    public function test_clear_reservation_makes_gift_available_again(): void
    {
        $gift = Gift::factory()->reserved()->create();
        $gift->clearReservation();

        $gift->refresh();
        $this->assertEquals('N', $gift->reservado);
        $this->assertNull($gift->reservado_ate);
        $this->assertTrue($gift->isAvailable());
    }

    public function test_valor_cota_returns_per_quota_price(): void
    {
        $gift = Gift::factory()->quotaBased(10)->create(['valor' => '500.00']);
        $this->assertEquals(50.00, $gift->valor_cota);
    }

    public function test_valor_cota_is_null_for_non_quota_gifts(): void
    {
        $gift = Gift::factory()->create(['valor' => '200.00']);
        $this->assertNull($gift->valor_cota);
    }

    public function test_remaining_quotas_returns_zero_for_non_quota_gifts(): void
    {
        $gift = Gift::factory()->create();
        $this->assertEquals(0, $gift->remainingQuotas());
    }

    public function test_reservado_ate_is_serialized_as_iso_8601(): void
    {
        $gift = Gift::factory()->reserved(now()->addHour())->create();
        $array = $gift->toArray();

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}$/',
            $array['reservado_ate'],
        );
    }
}
