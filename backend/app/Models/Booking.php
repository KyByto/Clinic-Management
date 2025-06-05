<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'client_id',
        'offer_id',
        'booking_date',
        'status',
        'notes',
        'payment_status',
        'booking_time',
    ];

    /**
     * The attributes that should be cast
     */
    protected $casts = [
        'booking_date' => 'datetime',
    ];

    /**
     * Get the client that owns the booking
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the offer associated with this booking
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
