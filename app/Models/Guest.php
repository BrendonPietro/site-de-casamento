<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guest extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory;

    /**
     * Tipos de convidado que têm acesso ao painel (noivos).
     */
    public const TIPOS_PAINEL = ['noivo', 'noiva'];

    protected $table = 'guests';

    protected $fillable = [
        'nome_completo',
        'telefone',
        'email',
        'senha',
        'tipo',
        'sexo',
        'presenca_confirmada',
        'interesse_van',
        'numero_calcado',
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

    public function isFeminino(): bool
    {
        return $this->sexo === 'F';
    }

    public function isPresencaConfirmada(): bool
    {
        return $this->presenca_confirmada === 'S';
    }

    /**
     * Apenas noivo/noiva conseguem entrar no painel administrativo.
     */
    public function isDono(): bool
    {
        return in_array($this->tipo, self::TIPOS_PAINEL, true);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isDono();
    }

    public function getFilamentName(): string
    {
        return $this->nome_completo;
    }

    /**
     * Guests whose attendance I can confirm (I am responsible for them).
     */
    public function dependentes()
    {
        return $this->belongsToMany(
            Guest::class,
            'delegacoes_presenca',
            'convidado_responsavel_id',
            'convidado_dependente_id'
        );
    }

    /**
     * Guests who can confirm my attendance (they are responsible for me).
     */
    public function responsaveis()
    {
        return $this->belongsToMany(
            Guest::class,
            'delegacoes_presenca',
            'convidado_dependente_id',
            'convidado_responsavel_id'
        );
    }
}
