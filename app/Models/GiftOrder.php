<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftOrder extends Model
{
    use HasFactory;

    protected $table = 'gift_orders';

    protected $fillable = [
        'gift_id',
        'guest_name',
        'guest_email',
        'message',
        'unit_value',
        'quantity',
        'total_value',
        'status',
        'external_reference',
        'mp_payment_id',
        'mp_preference_id',
        'mp_status',
        'mp_status_detail',
        'payment_method',
        'reserved_until',
        'paid_at',
    ];

    protected $casts = [
        'unit_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'reserved_until' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function gift(): BelongsTo
    {
        return $this->belongsTo(Gift::class, 'gift_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'P';
    }

    public function isApproved(): bool
    {
        return $this->status === 'A';
    }
}
