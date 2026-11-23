<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_orders', function (Blueprint $table) {
            $table->id();

            // relacionamento
            $table->foreignId('gift_id')->constrained()->cascadeOnDelete();

            // Dados do convidado
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->text('message')->nullable();

            // Valores da compra
            $table->decimal('unit_value', 10, 2);
            $table->unsignedInteger('quantity')->nullable(); // usado para cotas
            $table->decimal('total_value', 10, 2);

            // Status interno do pedido
            // P = pending
            // A = approved
            // R = refused
            // E = expired
            // C = cancelled
            $table->char('status', 1)->default('P');

            // mercado pago
            $table->string('external_reference')->unique();
            $table->string('mp_payment_id')->nullable();
            $table->string('mp_preference_id')->nullable();
            $table->string('mp_status')->nullable();
            $table->string('mp_status_detail')->nullable();
            $table->string('payment_method')->nullable();

            // Controle de reserva / pagamento
            $table->timestamp('reserved_until')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_orders');
    }
};
