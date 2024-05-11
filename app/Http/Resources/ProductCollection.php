<?php

namespace App\Http\Resources;

use App\Models\FavoriteProduct;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProviderCategoryResource;


class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'list' => $this->collection->transform(function ($query ){
                $arr = [array(asset('/uploads/products/default.jpeg'))];
                return [
                    'id'    => $query->id,
                    'provider' => new ProviderResource($query->provider),
                    'activity'  => activity($query->activity),
                    'name'     => app()->getLocale() == 'ar' ? $query->name : ($query->name_en?:$query->name),
                    'description' => strip_tags(str_replace('&nbsp;', ' ', $query->description)),
                    'price_before_discount_boolean' => ($query->price_before_discount == 0 or $query->price_before_discount == null) ? 'false' : 'true',
                    'price_before_discount' => $query->price_before_discount,
                    'price'    => $query->price,
                    'less_amount' => $query->less_amount,
                    'product_requirements' => strip_tags(str_replace('&nbsp;', ' ', $query->product_requirements)),
                    'photos' => $query->photos->count() > 0 ? ProductPhoto::collection($query->photos) : null,
                    'provider_category' =>$query->category == null ? null : new ProviderCategoryResource($query->category),
                    'sizes'       => SizeResource::collection($query->sizes),
                    'options'     => OptionResource::collection($query->options),
                    'created_at' => $query->created_at->format('Y-m-d')
                ];
            }),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => url('/api/v1/products'),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
