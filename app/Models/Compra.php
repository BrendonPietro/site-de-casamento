<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'convidado_id',
        'presente_id',
        'is_cota',
        'quantidade_cotas',
        'valor_pago',
        'metodo_pagamento',
        'mensagem',
    ];

    protected $casts = [
        'valor_pago' => 'decimal:2',
    ];

    public function isCota(): bool
    {
        return $this->is_cota === 'S';
    }

    public function convidado()
    {
        return $this->belongsTo(Convidado::class, 'convidado_id');
    }

    public function presente()
    {
        return $this->belongsTo(Gift::class, 'presente_id');
    }
}
