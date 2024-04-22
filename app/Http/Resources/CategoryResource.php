<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->category)
        {
            return [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'icon' => $this->category->icon == null ? null : asset('/uploads/categories/' . $this->category->icon),
                'has_sub_categories' => $this->category->sub_categories->count() > 0 ? true : false,
                'arrange'  => $this->category->arrange,
                'created_at' => $this->category->created_at->format('Y-m-d'),
            ];
        }else{
            return [
                'id'   => $this->id,
                'name' => $this->name,
                'icon' => $this->icon == null ? null : asset('/uploads/categories/' . $this->icon),
                'has_sub_categories' => $this->sub_categories->count() > 0 ? true : false,
                'arrange'  => $this->arrange,
                'created_at' => $this->created_at->format('Y-m-d'),
            ];
        }
    }
}
