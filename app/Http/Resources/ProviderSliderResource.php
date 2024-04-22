<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'provider_id' => $this->provider_id,
            'photo' => asset('/uploads/providers/sliders/' . $this->photo),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
