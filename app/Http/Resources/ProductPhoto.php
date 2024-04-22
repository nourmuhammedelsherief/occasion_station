<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPhoto extends JsonResource
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
            'id'   => $this->id,
            'product_id' => $this->product_id,
            'photo' => asset('/uploads/products/' . $this->photo),
        ];
    }
}
