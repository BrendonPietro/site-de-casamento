<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Gift extends Model
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
        'reservado',
        'reservado_ate',
        'foto',
        'categoria',
        'favorito',
        'ativo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'reservado_ate' => 'datetime',
    ];

    protected $appends = ['foto_url', 'valor_cota'];

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        $slug = \Illuminate\Support\Str::slug($this->nome);
        return asset('storage/gifts/' . $slug . '.png');
    }

    public function getValorCotaAttribute(): ?float
    {
        if (!$this->isQuotaBased() || !$this->num_cotas) {
            return null;
        }
        return round((float) $this->valor / (int) $this->num_cotas, 2);
    }

    public function getCotasRestantesAttribute(): ?int
    {
        if (!$this->isQuotaBased() || !$this->num_cotas) {
            return null;
        }
        return $this->num_cotas - $this->cotas_vendidas;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->toIso8601String();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(GiftOrder::class, 'gift_id');
    }

    public function isActive(): bool
    {
        return $this->ativo === 'S';
    }

    public function isSold(): bool
    {
        return $this->vendido === 'S';
    }

    public function isReservable(): bool
    {
        return $this->cotavel !== 'S';
    }

    public function isQuotaBased(): bool
    {
        return $this->cotavel === 'S';
    }

    public function isReserved(): bool
    {
        if ($this->reservado !== 'S') {
            return false;
        }

        if (!$this->reservado_ate) {
            return false;
        }

        return $this->reservado_ate->isFuture();
    }

    public function remainingQuotas(): int
    {
        if (!$this->isQuotaBased()) {
            return 0;
        }

        return max(0, (int) $this->num_cotas - (int) $this->cotas_vendidas);
    }

    public function isAvailable(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->isSold()) {
            return false;
        }

        if ($this->isQuotaBased()) {
            return $this->remainingQuotas() > 0;
        }

        return !$this->isReserved();
    }

    public function reserveUntil(Carbon $until): void
    {
        $this->update([
            'reservado' => 'S',
            'reservado_ate' => $until,
        ]);
    }

    public function clearReservation(): void
    {
        $this->update([
            'reservado' => 'N',
            'reservado_ate' => null,
        ]);
    }
}
