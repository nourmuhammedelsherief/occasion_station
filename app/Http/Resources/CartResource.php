<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sale = 0;
        $rent = 0;
        if ($this->orders->count() > 0)
        {
            foreach ($this->orders as $order)
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
            'cart_id'     => $this->id,
            'user'        => new User($this->user),
            'items'       => OrderResource::collection($this->orders),
            'items_price' => $this->items_price,
            'delivery_price' => $this->delivery_price,
            'tax_value'     => $this->tax_value,
            'tax_percent' => Setting::find(1)->tax,
            'total_price' => $this->total_price,
            'payment_status' => $this->payment_status,
            'payment_type' => $this->payment_type,
            'status'  => status($this->status),
            'delivery_date' => $this->delivery_date,
            'delivery_time' => $this->delivery_time,
            'delivery_latitude' => $this->delivery_latitude,
            'delivery_longitude' => $this->delivery_longitude,
            'delivery_address' => $this->delivery_address,
            'more_details' => $this->more_details,
            'activity'     => $activity,
            'created_at'     => $this->created_at->format('Y-m-d'),
        ];
    }
}
