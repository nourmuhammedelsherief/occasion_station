<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->sub_category != null)
        {
            return [
                'id'  => $this->sub_category->id,
                'category' => new CategoryResource($this->sub_category->category),
                'name' => $this->sub_category->name,
                'created_at'  => $this->sub_category->created_at->format('Y-m-d'),
            ];
        }else{
            return [
                'id'  => $this->id,
                'category' => new CategoryResource($this->category),
                'name' => $this->name,
                'created_at'  => $this->created_at->format('Y-m-d'),
            ];
        }
    }
}
