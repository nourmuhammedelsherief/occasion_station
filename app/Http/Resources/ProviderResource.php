<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'id'             => $this->id,
            'city'           => new CityResource($this->city),
            'name'           => app()->getLocale() == 'ar' ? $this->name : ($this->name_en ?: $this->name),
            'phone_number'   => $this->phone_number,
            'email'          => $this->email,
            'address'        => $this->address,
            'photo'          => $this->photo == null ? null : asset('/uploads/providers/' . $this->photo),
            'logo'           => $this->logo == null ? asset('/uploads/providers/logos/default.jpeg') : asset('/uploads/providers/logos/' . $this->logo),
            'activity'       => activity($this->activity),
            'special'        => $this->special,
            'vip'            => $this->vip,
            'show_cart'      => $this->show_cart,
            'arrange'        => $this->arrange,
            'category'       => CategoryResource::collection($this->provider_main_categories),
            'sub_categories' => SubCategoryResource::collection($this->provider_categories),
            'description'    => app()->getLocale() == 'ar' ? $this->description : $this->description_en,
            'bank_payment'   => $this->bank_payment,
            'online_payment' => $this->online_payment,
            'tamara_payment' => $this->tamara_payment,
            'rate'           => $this->rate,
            'delivery'       => $this->delivery,
            'delivery_by'    => $this->delivery_by,
            'store_receiving' => $this->store_receiving,
            'delivery_price'  => $this->delivery_price,
            'created_at'     => $this->created_at->format('Y-m-d'),
        ];
    }
}
