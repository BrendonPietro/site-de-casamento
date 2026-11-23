<?php

namespace Tests\Feature;

use App\Filament\Resources\GiftOrderResource\Pages\ListGiftOrders;
use App\Models\Gift;
use App\Models\GiftOrder;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function noivo(): Guest
    {
        return Guest::create([
            'nome_completo' => 'Brendon Teste',
            'senha' => bcrypt('secret'),
            'tipo' => 'noivo',
            'sexo' => 'M',
        ]);
    }

    private function convidado(string $nome = 'Fulano Convidado'): Guest
    {
        return Guest::create([
            'nome_completo' => $nome,
            'senha' => bcrypt('secret'),
            'tipo' => 'convidado',
        ]);
    }

    public function test_nao_logado_redireciona_para_login(): void
    {
        $this->get('/painel')->assertRedirect('/painel/login');
    }

    public function test_convidado_comum_e_redirecionado_para_o_site(): void
    {
        $this->actingAs($this->convidado(), 'guest')
            ->get('/painel')
            ->assertRedirect(route('home'));
    }

    public function test_noivo_renderiza_paginas_do_painel(): void
    {
        $noivo = $this->noivo();
        $convidado = $this->convidado('Maria Convidada');
        Gift::create(['nome' => 'Liquidificador', 'valor' => 150, 'categoria' => 'Cozinha']); // foto = null -> fallback por slug
        Gift::create(['nome' => 'Cota Lua de Mel', 'valor' => 1000, 'cotavel' => 'S', 'num_cotas' => 10, 'cotas_vendidas' => 3]);

        $this->actingAs($noivo, 'guest');

        $this->get('/painel')->assertOk();
        $this->get('/painel/guests')->assertOk();
        $this->get('/painel/guests/create')->assertOk();
        $this->get('/painel/guests/' . $convidado->getKey() . '/edit')->assertOk();
        $this->get('/painel/gifts')->assertOk();
        $this->get('/painel/gifts/create')->assertOk();
        $this->get('/painel/gift-orders')->assertOk();
    }

    public function test_visualizar_pedido_renderiza_infolist(): void
    {
        $noivo = $this->noivo();
        $gift = Gift::create(['nome' => 'Jogo de panelas', 'valor' => 200]);
        $order = GiftOrder::create([
            'gift_id' => $gift->getKey(),
            'guest_name' => 'Tio João',
            'guest_email' => 'tio@example.com',
            'message' => 'Felicidades ao casal!',
            'unit_value' => 200,
            'total_value' => 200,
            'status' => 'A',
            'external_reference' => 'ext-test-1',
            'payment_method' => 'pix',
        ]);

        $this->actingAs($noivo, 'guest');

        Livewire::test(ListGiftOrders::class)
            ->mountTableAction('view', $order)
            ->assertTableActionMounted('view')
            ->assertSee('Felicidades ao casal!');
    }
}
