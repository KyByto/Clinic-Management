<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offer_id',
        'client_id',
        'booking_date',
        'booking_time',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the offer that owns the booking.
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the client that owns the booking.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the payment associated with the booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
