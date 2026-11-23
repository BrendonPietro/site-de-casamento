<?php

namespace Database\Factories;

use App\Models\Gift;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GiftOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gift_id'            => Gift::factory(),
            'guest_name'         => $this->faker->name(),
            'guest_email'        => $this->faker->safeEmail(),
            'message'            => null,
            'unit_value'         => 100.00,
            'quantity'           => null,
            'total_value'        => 100.00,
            'status'             => 'P',
            'external_reference' => (string) Str::uuid(),
            'mp_payment_id'      => null,
            'mp_preference_id'   => null,
            'mp_status'          => null,
            'mp_status_detail'   => null,
            'payment_method'     => null,
            'reserved_until'     => now()->addMinutes(25),
            'paid_at'            => null,
        ];
    }

    public function approved(): static
    {
        return $this->state([
            'status'  => 'A',
            'paid_at' => now(),
        ]);
    }

    public function forGift(Gift $gift): static
    {
        return $this->state([
            'gift_id'     => $gift->id,
            'unit_value'  => (float) $gift->valor,
            'total_value' => (float) $gift->valor,
        ]);
    }
}
