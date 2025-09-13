<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'city' => $this->city,
            'address' => $this->address,
            'bith_date' => $this->bith_date->format('Y/m/d'),

            'zip_code' => $this->zip_code,
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
