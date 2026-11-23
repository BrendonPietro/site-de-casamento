<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de ligação: quem pode confirmar presença de quem
        // convidado_responsavel_id pode confirmar a presença de convidado_dependente_id
        Schema::create('delegacoes_presenca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convidado_responsavel_id')
                  ->constrained('guests')
                  ->cascadeOnDelete();
            $table->foreignId('convidado_dependente_id')
                  ->constrained('guests')
                  ->cascadeOnDelete();
            $table->timestamps();

            // Evita duplicatas
            $table->unique(['convidado_responsavel_id', 'convidado_dependente_id'], 'delegacao_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegacoes_presenca');
    }
};
