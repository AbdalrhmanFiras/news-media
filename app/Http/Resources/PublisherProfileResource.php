<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name . $this->last_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'governorate' => $this->governorate,
            'city' => $this->city,
            'address' => $this->address,
            'bith_date' => $this->bith_date->format('Y/m/d'),
            'bio' => $this->bio,
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
