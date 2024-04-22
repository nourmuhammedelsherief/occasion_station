<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
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
                $sale = 0;
                $rent = 0;
                if ($query->orders->count() > 0)
                {
                    foreach ($query->orders as $order)
                    {
                        if ($order->product->activity == 'sale')
                        {
                            $sale++;
                        }else{
                            $rent++;
                        }
                    }
                }
                if ($rent != 0 &&  $sale != 0)
                {
                    $activity = 'بيع و تأجير';
                }elseif ($rent == 0  &&  $sale != 0)
                {
                    $activity = 'بيع';
                }elseif ($rent != 0  &&  $sale == 0)
                {
                    $activity = 'تأجير';
                }elseif ($rent == 0  &&  $sale == 0)
                {
                    $activity = 'لا يوجد منتجات';
                }else{
                    $activity = 'بيع و تأجير';
                }
                return [
                    'cart_id'     => $query->id,
                    'user'        => new User($query->user),
                    'items'       => OrderResource::collection($query->orders),
                    'items_price' => $query->items_price,
                    'delivery_price' => $query->delivery_price,
                    'tax_value'     => $query->tax_value,
                    'tax_percent' => Setting::find(1)->tax,
                    'total_price' => $query->total_price,
                    'payment_status' => $query->payment_status,
                    'payment_type' => $query->payment_type,
                    'status'  => status($query->status),
                    'delivery_date' => $query->delivery_date,
                    'delivery_time' => $query->delivery_time,
                    'delivery_latitude' => $query->delivery_latitude,
                    'delivery_longitude' => $query->delivery_longitude,
                    'delivery_address' => $query->delivery_address,
                    'more_details' => $query->more_details,
                    'activity'     => $activity,
                    'created_at'     => $query->created_at->format('Y-m-d'),
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
