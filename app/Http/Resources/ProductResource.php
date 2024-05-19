<?php

namespace App\Http\Resources;

use App\Models\FavoriteProduct;
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
            'name'     => app()->getLocale() == 'ar' ? $this->name : ($this->name_en?:$this->name_ar),
            'description' => app()->getLocale() == 'ar' ? strip_tags(str_replace('&nbsp;', ' ', $this->description)) : strip_tags(str_replace('&nbsp;', ' ', $this->description_en)),
            'price_before_discount_boolean' => ($this->price_before_discount == 0 or $this->price_before_discount == null) ? 'false' : 'true',
            'price_before_discount' => $this->price_before_discount,
            'price'    => $this->price,
            'less_amount' => $this->less_amount,
            'product_requirements' => app()->getLocale() == 'ar' ? strip_tags(str_replace('&nbsp;', ' ', $this->product_requirements)) : strip_tags(str_replace('&nbsp;', ' ', $this->product_requirements_en)),
            'photos' => $this->photos->count() > 0 ? ProductPhoto::collection($this->photos) : null,
            'provider_category' => $this->category == null ? null : new ProviderCategoryResource($this->category),
            'sizes'       => SizeResource::collection($this->sizes),
            'modifiers'   => ProductModifierResource::collection($this->modifiers),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
