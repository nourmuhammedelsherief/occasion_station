<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderCategoryResource extends JsonResource
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
                'id' => $this->category->id,
                'name' => app()->getLocale() == 'ar' ? $this->category->name : ($this->category->name_en ?: $this->category->name),
                'created_at' => $this->category->created_at->format('Y-m-d'),
            ];
        }else{
            return [
                'id' => $this->id,
                'name' => app()->getLocale() == 'ar' ? $this->name : ($this->name_en ?: $this->name),
                'created_at' => $this->created_at->format('Y-m-d'),
            ];
        }
    }
}
