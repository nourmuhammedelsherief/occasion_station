<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'       => $this->id,
            'provider' => new ProviderResource($this->provider),
            'user'     => new User($this->user),
            'product'  => new ProductResource($this->product),
//            'size'     => $this->size_id ? new SizeResource($this->size) : null,
//            'options'  => OrderOptionResource::collection($this->options),
            'tax_value' => $this->tax_value,
            'product_count' => $this->product_count,
            'order_price'   => $this->order_price,
            'store_receiving' => $this->store_receiving,
            'created_at'    => $this->created_at->format('Y-m-d'),
        ];
    }
}
