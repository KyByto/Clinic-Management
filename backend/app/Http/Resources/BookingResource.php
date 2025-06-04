<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_date' => $this->booking_date,
            'booking_time' => $this->booking_time,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'notes' => $this->notes,
            'offer' => [
                'id' => $this->offer->id,
                'title' => $this->offer->title,
                'price' => $this->offer->price,
            ],
            'client' => [
                'name' => $this->client->name,
                'email' => $this->client->email,
                'phone' => $this->client->phone,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
