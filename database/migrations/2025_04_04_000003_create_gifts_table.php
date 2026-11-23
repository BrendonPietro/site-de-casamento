<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('valor', 10, 2);

            $table->char('cotavel', 1)->default('N');
            $table->unsignedInteger('num_cotas')->nullable();
            $table->unsignedInteger('cotas_vendidas')->default(0);

            $table->char('vendido', 1)->default('N');
            $table->char('reservado', 1)->default('N');
            $table->timestamp('reservado_ate')->nullable();

            $table->string('foto')->nullable();
            $table->string('categoria')->nullable();
            $table->char('favorito', 1)->default('N');
            $table->char('ativo', 1)->default('S');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
