<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollection extends ResourceCollection
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
                    'id'             => $query->id,
                    'city'           => new CityResource($query->city),
                    'name'           => $query->name,
                    'phone_number'   => $query->phone_number,
                    'email'          => $query->email,
                    'address'        => $query->address,
                    'photo'          => $query->photo == null ? null : asset('/uploads/providers/' . $query->photo),
                    'logo'           => $query->logo == null ? asset('/uploads/providers/logos/default.jpeg') : asset('/uploads/providers/logos/' . $query->logo),
                    'activity'       => activity($query->activity),
                    'special'        => $query->special,
                    'vip'            => $query->vip,
                    'arrange'        => $query->arrange,
                    'category'       => CategoryResource::collection($query->provider_main_categories),
                    'sub_categories' => SubCategoryResource::collection($query->provider_categories),
                    'description'  => $query->description,
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
