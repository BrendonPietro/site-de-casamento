<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Convidado extends Authenticatable
{
    use HasFactory;

    protected $table = 'guests';

    protected $fillable = [
        'nome_completo',
        'telefone',
        'email',
        'senha',
        'tipo',
        'presenca_confirmada',
        'interesse_van',
        'ultimo_acesso',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'ultimo_acesso' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function isPresencaConfirmada(): bool
    {
        return $this->presenca_confirmada === 'S';
    }

    /**
     * Convidados cuja presença EU posso confirmar (sou responsável por eles).
     */
    public function dependentes()
    {
        return $this->belongsToMany(
            Convidado::class,
            'delegacoes_presenca',
            'convidado_responsavel_id',
            'convidado_dependente_id'
        );
    }

    /**
     * Convidados que podem confirmar MINHA presença (são responsáveis por mim).
     */
    public function responsaveis()
    {
        return $this->belongsToMany(
            Convidado::class,
            'delegacoes_presenca',
            'convidado_dependente_id',
            'convidado_responsavel_id'
        );
    }

    /**
     * Compras feitas por este convidado.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'convidado_id');
    }
}
