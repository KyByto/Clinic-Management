<?php

namespace App\Http\Requests;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'offer_id' => [
                'required',
                'integer',
                Rule::exists('offers', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'booking_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateDayAndTimeAvailability($validator);
        });
    }

    /**
     * Validate if the selected day and time are available for the offer
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateDayAndTimeAvailability($validator)
    {
        if ($validator->errors()->count() > 0) {
            return;
        }

        $offer = Offer::find($this->offer_id);
        if (!$offer) {
            \Log::error('Offer not found', ['offer_id' => $this->offer_id]);
            $validator->errors()->add('offer_id', 'Offer not found');
            return;
        }

        // Débogage: log des détails de l'offre
        \Log::info('Offer details', [
            'id' => $offer->id,
            'active' => $offer->active ?? null,
            'is_active' => $offer->is_active ?? null,
            'available_days' => $offer->available_days ?? null
        ]);

        $bookingDate = Carbon::parse($this->booking_date);
        $day = strtolower($bookingDate->format('l')); // Get day name in lowercase
        $time = $this->booking_time;

        // Débogage: log des paramètres de réservation
        \Log::info('Booking request details', [
            'date' => $this->booking_date,
            'day' => $day,
            'time' => $time
        ]);

        // Vérifier si available_days existe et est un tableau/objet accessible
        if (!isset($offer->available_days) || !is_array($offer->available_days) && !is_object($offer->available_days)) {
            \Log::error('Available days not properly defined', [
                'available_days' => $offer->available_days ?? null
            ]);
            $validator->errors()->add('booking_date', 'Schedule information is not available for this offer.');
            return;
        }

        // Vérifier si le jour est disponible
        if (!isset($offer->available_days[$day])) {
            \Log::error('Day not defined in available days', [
                'day' => $day,
                'available_days' => $offer->available_days
            ]);
            $validator->errors()->add('booking_date', "This offer has no schedule defined for {$day}.");
            return;
        }

        if (!isset($offer->available_days[$day]['available']) || $offer->available_days[$day]['available'] !== true) {
            \Log::error('Day not available', [
                'day' => $day,
                'available' => $offer->available_days[$day]['available'] ?? null
            ]);
            $validator->errors()->add('booking_date', "This offer is not available on {$day}.");
            return;
        }

        // Check if time is within available hours
        $startTime = $offer->available_days[$day]['start_time'] ?? null;
        $endTime = $offer->available_days[$day]['end_time'] ?? null;

        if (!$startTime || !$endTime) {
            \Log::error('Time range not defined', [
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
            $validator->errors()->add('booking_time', "Time range not defined for {$day}.");
            return;
        }

        try {
            $bookingTime = Carbon::createFromFormat('H:i', $time);
            $startTimeObj = Carbon::createFromFormat('H:i', $startTime);
            $endTimeObj = Carbon::createFromFormat('H:i', $endTime);

            \Log::info('Time validation', [
                'booking_time' => $bookingTime->format('H:i'),
                'start_time' => $startTimeObj->format('H:i'),
                'end_time' => $endTimeObj->format('H:i'),
                'is_after_start' => $bookingTime->gte($startTimeObj),
                'is_before_end' => $bookingTime->lte($endTimeObj)
            ]);

            if ($bookingTime->lt($startTimeObj) || $bookingTime->gt($endTimeObj)) {
                $validator->errors()->add(
                    'booking_time', 
                    "This offer is only available between {$startTime} and {$endTime} on {$day}."
                );
            }
        } catch (\Exception $e) {
            \Log::error('Time format error', [
                'error' => $e->getMessage(),
                'booking_time' => $time,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
            $validator->errors()->add('booking_time', "Invalid time format provided.");
        }
    }
}
