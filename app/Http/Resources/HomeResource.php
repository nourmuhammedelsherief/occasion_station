<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use DB;
class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $sliders = Slider::with('provider' , 'product')
//            ->whereHas('provider' , function ($p) use ($request){
//
//            });
        return [
            'categories' => CategoryResource::collection(Category::orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')->get()),
            'sliders'    => SliderResource::collection(Slider::all()),
            'products'   => ProductResource::collection(Product::all()->take(1)),
            'contact_number' => Setting::first()->contact_number,
            'whatsaaAppMessage' => Setting::first()->contact_text,
        ];
    }
}
