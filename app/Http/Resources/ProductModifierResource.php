<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductModifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'name'       => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'details'    => app()->getLocale() == 'ar' ? $this->details_ar : $this->details_en,
            'count'      => $this->count,
            'options'    => OptionResource::collection($this->options),
        ];
    }
}
