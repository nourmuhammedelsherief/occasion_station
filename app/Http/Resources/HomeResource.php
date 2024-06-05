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
        $sliders = Slider::with('provider' , 'product')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city', function ($d) use ($request) {
                    $d->where('google_city_id', $request->google_city_id);
                });
            })
            ->orWhereHas('product' , function ($p) use ($request){
                $p->with('provider');
                $p->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city', function ($d) use ($request) {
                        $d->where('google_city_id', $request->google_city_id);
                    });
                });
            })
            ->get();

        $products = Product::with('provider')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city', function ($d) use ($request) {
                    $d->where('google_city_id', $request->google_city_id);
                });
            })
            ->get()
            ->take(1);
        return [
            'categories' => CategoryResource::collection(Category::orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')->get()),
            'sliders'    => SliderResource::collection($sliders),
            'products'   => ProductResource::collection($products),
            'contact_number' => Setting::first()->contact_number,
            'whatsaaAppMessage' => Setting::first()->contact_text,
        ];
    }
}
