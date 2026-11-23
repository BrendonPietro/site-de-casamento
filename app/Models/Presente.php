<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presente extends Model
{
    use HasFactory;

    protected $table = 'gifts';

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'cotavel',
        'num_cotas',
        'cotas_vendidas',
        'vendido',
        'foto',
        'categoria',
        'favorito',
        'ativo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    protected $appends = ['foto_url', 'valor_cota'];

    /**
     * URL pública da foto do presente.
     * Se não tiver foto no banco, gera o nome pelo slug do nome do presente.
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        // Gera nome do arquivo a partir do nome do presente
        $slug = \Illuminate\Support\Str::slug($this->nome);
        return asset('storage/gifts/' . $slug . '.png');
    }

    public function isCotavel(): bool
    {
        return $this->cotavel === 'S';
    }

    public function isVendido(): bool
    {
        return $this->vendido === 'S';
    }

    public function isAtivo(): bool
    {
        return $this->ativo === 'S';
    }

    public function isFavorito(): bool
    {
        return $this->favorito === 'S';
    }

    /**
     * Compras deste presente.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'presente_id');
    }

    /**
     * Valor de cada cota (se cotável).
     */
    public function getValorCotaAttribute(): ?float
    {
        if (!$this->isCotavel() || !$this->num_cotas) {
            return null;
        }
        return round($this->valor / $this->num_cotas, 2);
    }

    /**
     * Cotas restantes.
     */
    public function getCotasRestantesAttribute(): ?int
    {
        if (!$this->isCotavel() || !$this->num_cotas) {
            return null;
        }
        return $this->num_cotas - $this->cotas_vendidas;
    }
}
