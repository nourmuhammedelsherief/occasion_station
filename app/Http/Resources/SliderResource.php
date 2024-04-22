<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'title' => $this->title,
            'photo' => asset('/uploads/sliders/' . $this->photo),
            'provider_id' => $this->provider_id == null ? null : new ProviderResource($this->provider),
            'product_id' => $this->product_id == null ? null : new ProductResource($this->product),
            'outer_url'  => $this->outer_url == null ? null : $this->outer_url,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
