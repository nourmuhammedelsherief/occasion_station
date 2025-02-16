<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollectionTest extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $this->lang = $request->header('Content-Language');
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection->transform(function ($query){
                return [
                    'id'             => $query->provider_id ?: $query->id,
                    'city'           => new CityResource($query->city),
                    'name'           => app()->getLocale() == 'ar' ? $query->name : ($query->name_en ?: $query->name),
                    'phone_number'   => $query->phone_number,
                    'email'          => $query->email,
                    'address'        => $query->address,
                    'photo'          => $query->photo == null ? null : asset('/uploads/providers/' . $query->photo),
                    'logo'           => $query->logo == null ? asset('/uploads/providers/logos/default.jpeg') : asset('/uploads/providers/logos/' . $query->logo),
                    'activity'       => activity($query->activity),
                    'special'        => $query->special,
                    'vip'            => $query->vip,
                    'show_cart'      => $query->show_cart,
                    'arrange'        => $query->arrange,
                    'category'       => CategoryResource::collection($query->provider_main_categories),
                    'sub_categories' => SubCategoryResource::collection($query->provider_categories),
                    'description'    => app()->getLocale() == 'ar' ? $query->description : $query->description_en,
                    'bank_payment'   => $query->bank_payment,
                    'online_payment' => $query->online_payment,
                    'tamara_payment' => $query->tamara_payment,
                    'rate'           => $query->rate,
                    'delivery'       => $query->delivery,
                    'delivery_by'    => $query->delivery_by,
                    'store_receiving' => $query->store_receiving,
                    'delivery_price'  => $query->delivery_price,
                    'created_at'     => $query->created_at->format('Y-m-d'),
                ];
            }),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => url('/api/v1/providers'),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
