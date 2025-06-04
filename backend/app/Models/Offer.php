<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'title',
        'description',
        'price',
        'image_path',
        'is_active',
        'available_days',
    ];

    protected $casts = [
        'available_days' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the clinic that owns the offer.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the bookings for the offer.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
