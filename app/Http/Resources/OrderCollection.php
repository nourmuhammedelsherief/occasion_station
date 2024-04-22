<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($query){
                return [
                    'id'       => $query->id,
                    'provider' => new ProviderResource($query->provider),
                    'user'     => new User($query->user),
                    'product'  => new ProductResource($query->product),
                    'tax_vlaue' => $query->tax_value,
                    'product_count' => $query->product_count,
                    'order_price'   => $query->order_price,
                    'store_receiving' => $query->store_receiving,
                    'created_at'    => $query->created_at->format('Y-m-d'),
                ];
            }),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => url('/api/v1/orders'),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
