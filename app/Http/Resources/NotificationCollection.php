<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
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
        return
            $this->collection->transform(function ($query){
//                $locale = $this->lang;
                return [
                    'id'          =>$query->id,
                    'user'        =>$query->user == null ? null : $query->user->id,
                    'order'       =>$query->order == null ? null : $query->order->id,
                    'title'       =>$query->title,
                    'message'     =>$query->message,
                    'type'        =>$query->type,
                    'is_read'     =>$query->is_read,
                    'created_at'  => $query->created_at->format('Y-m-d'),
                ];
            });
    }
}
