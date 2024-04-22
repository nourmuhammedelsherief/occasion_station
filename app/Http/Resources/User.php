<?php

namespace App\Http\Resources;

use App\Rate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class User extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $locale = $request->header('Content-Language');
        return [
            'id'            =>$this->id,
            'city'           => new CityResource($this->city),
            'name'          =>$this->name,
            'phone_number'  =>$this->phone_number,
            'active'        =>$this->active,
            'photo'         =>$this->photo == null ? asset('/uploads/users/default.png') : asset('/uploads/users/'.$this->photo),
            'api_token'     =>$this->api_token,
            'created_at'    =>$this->created_at->format('Y-m-d'),
        ];
    }
}

