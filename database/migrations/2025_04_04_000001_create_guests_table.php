<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('senha');
            $table->enum('tipo', [
                'convidado',
                'madrinha',
                'padrinho',
                'mae',
                'pai',
                'avo',
                'ava',
                'familiar',
                'noivo',
                'noiva',
            ])->default('convidado');
            $table->char('presenca_confirmada', 1)->default('N');
            $table->timestamp('ultimo_acesso')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
