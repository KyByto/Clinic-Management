<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OfferResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'image_path' => $this->image_path ? url(Storage::url($this->image_path)) : null,
            'is_active' => $this->is_active,
            'available_days' => $this->available_days,
            'clinic' => [
                'id' => $this->clinic->id,
                'name' => $this->clinic->name,
                'address' => $this->clinic->address,
                'phone' => $this->clinic->phone,
                'email' => $this->clinic->email,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
