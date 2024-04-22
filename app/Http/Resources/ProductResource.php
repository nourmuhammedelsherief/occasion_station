<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProviderCategoryResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $photo = [(asset('/uploads/products/default.jpeg'))];
        $arr = [$photo];
        return [
            'id'    => $this->id,
            'provider' => new ProviderResource($this->provider),
            'activity'  => activity($this->activity),
            'name'     => $this->name,
            'description' => strip_tags(str_replace('&nbsp;', ' ', $this->description)),
            'price_before_discount_boolean' => ($this->price_before_discount == 0 or $this->price_before_discount == null) ? 'false' : 'true',
            'price_before_discount' => $this->price_before_discount,
            'price'    => $this->price,
            'less_amount' => $this->less_amount,
            'product_requirements' => strip_tags(str_replace('&nbsp;', ' ', $this->product_requirements)),
            'store_receiving'  => $this->delivery_by ? ($this->delivery_by == 'app' ? 'true' : 'false') : 'false',
            'price_with_delivery' => $this->delivery,
            'delivery_by' => $this->delivery == 'yes' ? null : $this->delivery_by,
            'delivery_price' => ($this->delivery == 'no' and $this->delivery_by == 'app') ? $this->delivery_price : null,
            'photos' => $this->photos->count() > 0 ? ProductPhoto::collection($this->photos) : null,
            'provider_category' => $this->category == null ? null : new ProviderCategoryResource($this->category),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
