<?php

namespace Tests\Feature;

use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmacaoPresencaTest extends TestCase
{
    use RefreshDatabase;

    private function convidada(string $nome = 'Maria Convidada', array $extra = []): Guest
    {
        return Guest::create(array_merge([
            'nome_completo' => $nome,
            'senha' => bcrypt('secret'),
            'tipo' => 'convidado',
            'sexo' => 'F',
        ], $extra));
    }

    public function test_confirmar_presenca_salva_no_banco(): void
    {
        $guest = $this->convidada();

        $this->actingAs($guest, 'guest')
            ->post(route('confirmar-presenca.confirmar'), [
                'confirmados' => [$guest->id],
                'calcado' => [$guest->id => 37],
            ])
            ->assertRedirect(route('confirmar-presenca'));

        $guest->refresh();
        $this->assertSame('S', $guest->presenca_confirmada);
        $this->assertSame(37, (int) $guest->numero_calcado);
    }

    public function test_responsavel_confirma_dependente(): void
    {
        $responsavel = $this->convidada('Responsavel Grupo');
        $dependente = $this->convidada('Clara Cardozo');
        $responsavel->dependentes()->attach($dependente->id);

        $this->actingAs($responsavel, 'guest')
            ->post(route('confirmar-presenca.confirmar'), [
                'confirmados' => [$responsavel->id, $dependente->id],
                'calcado' => [$dependente->id => 39],
            ]);

        $this->assertSame('S', $dependente->fresh()->presenca_confirmada);
        $this->assertSame(39, (int) $dependente->fresh()->numero_calcado);
    }

    public function test_calcado_vazio_nao_apaga_valor_salvo(): void
    {
        // Input hidden vazio vira null (ConvertEmptyStringsToNull) e não deve
        // sobrescrever o número já salvo.
        $guest = $this->convidada('Maria Convidada', ['numero_calcado' => 39]);

        $this->actingAs($guest, 'guest')
            ->post(route('confirmar-presenca.confirmar'), [
                'confirmados' => [],
                'calcado' => [$guest->id => null],
            ]);

        $guest->refresh();
        $this->assertSame('N', $guest->presenca_confirmada);
        $this->assertSame(39, (int) $guest->numero_calcado);
    }

    public function test_formulario_renderiza_estado_atual_sem_depender_de_js(): void
    {
        $guest = $this->convidada('Maria Convidada', [
            'presenca_confirmada' => 'S',
            'numero_calcado' => 39,
        ]);

        $response = $this->actingAs($guest, 'guest')->get(route('confirmar-presenca'));

        // O checkbox precisa vir marcado do servidor: se o JS falhar, um novo
        // submit não pode resetar a presença pra 'N'.
        $response->assertOk();
        $response->assertSee('checked', false);
        $response->assertSee('value="39"', false);
    }

    /**
     * Loga pela rota real. actingAs() não serve aqui: ele troca o guard
     * default pra 'guest' (shouldUse), o que mascara o bug de produção —
     * lá o guard default segue 'web' e o perfil de cache do Spatie tratava
     * todo convidado logado como anônimo.
     */
    private function loginPelaRota(Guest $guest): void
    {
        $this->post(route('guest.login'), [
            'nome_completo' => $guest->nome_completo,
            'senha' => 'secret',
        ]);
    }

    public function test_pagina_de_presenca_nao_e_cacheada_entre_convidados(): void
    {
        config([
            'responsecache.enabled' => true,
            'responsecache.cache_store' => 'array',
        ]);

        $ana = $this->convidada('Ana Primeira');
        $bia = $this->convidada('Bia Segunda');

        $this->loginPelaRota($ana);
        $this->get(route('confirmar-presenca'))
            ->assertOk()
            ->assertSee('Ana Primeira');

        $this->post(route('guest.logout'));

        // Antes do GuestAwareCacheProfile + doNotCacheResponse, a Bia recebia
        // a página cacheada da Ana (com os IDs da Ana no formulário).
        $this->loginPelaRota($bia);
        $this->get(route('confirmar-presenca'))
            ->assertOk()
            ->assertSee('Bia Segunda')
            ->assertDontSee('Ana Primeira');
    }

    public function test_pagina_cacheada_da_home_nao_vaza_para_convidado_logado(): void
    {
        config([
            'responsecache.enabled' => true,
            'responsecache.cache_store' => 'array',
        ]);

        // Visita anônima popula o cache da home (versão sem menu de logado).
        $this->get(route('home'))->assertOk()->assertDontSee('Sair');

        // Convidado logado não pode receber a versão anônima cacheada.
        $guest = $this->convidada('Maria Logada');
        $this->loginPelaRota($guest);
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Sair');
    }
}
