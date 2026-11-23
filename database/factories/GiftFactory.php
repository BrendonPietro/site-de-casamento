<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'           => $this->faker->words(3, true),
            'descricao'      => $this->faker->sentence(),
            'valor'          => $this->faker->randomFloat(2, 50, 2000),
            'cotavel'        => 'N',
            'num_cotas'      => null,
            'cotas_vendidas' => 0,
            'vendido'        => 'N',
            'reservado'      => 'N',
            'reservado_ate'  => null,
            'foto'           => null,
            'categoria'      => 'Geral',
            'favorito'       => 'N',
            'ativo'          => 'S',
        ];
    }

    public function quotaBased(int $quotas = 5): static
    {
        return $this->state([
            'cotavel'   => 'S',
            'num_cotas' => $quotas,
        ]);
    }

    public function sold(): static
    {
        return $this->state(['vendido' => 'S']);
    }

    public function reserved(?\DateTimeInterface $until = null): static
    {
        return $this->state([
            'reservado'     => 'S',
            'reservado_ate' => $until ?? now()->addMinutes(25),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(['ativo' => 'N']);
    }
}
