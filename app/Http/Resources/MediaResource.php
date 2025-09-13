<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $disk = $this->disk ?? 'public';
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'full_url' =>  $this->full_url,
        ];
    }
}
