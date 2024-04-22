<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\HomeResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProviderCategoryResource;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\SliderResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Provider;
use App\Models\ProviderProductCategory;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use function foo\func;

class ProductController extends Controller
{
    public function products(Request $request)
    {
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'provider_category_id' => 'sometimes',
            'activity' => 'sometimes|in:rent,sale,all',
            'search' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        if($request->provider_category_id == 'all')
        {
            $products = Product::whereProviderId($request->provider_id)
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }elseif ($request->search != null and $request->activity == null and $request->provider_category_id == null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('name', 'LIKE', "%{$request->search}%")
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->paginate(10);
        } elseif ($request->search != null and $request->activity == null and $request->provider_category_id != null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('name', 'LIKE', "%{$request->search}%")
                ->where('category_id', $request->provider_category_id)
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }  elseif ($request->search == null and $request->provider_category_id == null and $request->activity != null) {
            if ($request->activity == 'all')
            {
                $products = Product::whereProviderId($request->provider_id)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }else{
                $products = Product::whereProviderId($request->provider_id)
                    ->where('activity', $request->activity)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }
        }elseif ($request->search == null and $request->provider_category_id != null and $request->activity != null) {
            if ($request->activity == 'all')
            {
                $products = Product::whereProviderId($request->provider_id)
                    ->where('category_id', $request->provider_category_id)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }else{
                $products = Product::whereProviderId($request->provider_id)
                    ->where('activity', $request->activity)
                    ->where('category_id', $request->provider_category_id)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }
        }elseif ($request->search == null and $request->provider_category_id != null and $request->activity == null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('category_id', $request->provider_category_id)
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->paginate(10);
        } elseif ($request->search != null and $request->activity != null and $request->provider_category_id != null) {
            if ($request->activity == 'all')
            {
                $products = Product::whereProviderId($request->provider_id)
                    ->where('name', 'LIKE', "%{$request->search}%")
                    ->where('category_id', $request->provider_category_id)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }else{
                $products = Product::whereProviderId($request->provider_id)
                    ->where('activity', $request->activity)
                    ->where('name', 'LIKE', "%{$request->search}%")
                    ->where('category_id', $request->provider_category_id)
                    ->whereStop('false')
                    ->orderBy('id' , 'desc')
                    ->paginate(10);
            }
        } else {
            $products = Product::whereProviderId($request->provider_id)
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }
        $categories = ProviderProductCategory::whereProviderId($request->provider_id)
            ->get();
        foreach ($categories as $category)
        {
            $check = Product::whereCategoryId($category->category_id)
                ->whereProviderId($category->provider_id)
                ->first();
            if ($check == null)
            {
                $category->delete();
            }
        }
        $all = [
            'products'   => new ProductCollection($products),
            'provider_categories' => ProviderCategoryResource::collection($categories),
        ];
        return ApiController::respondWithSuccessData($all);
    }

    public function providers_products_search(Request $request)
    {
        $rules = [
            'key_search' => 'sometimes|string|max:191',
            'activity' => 'sometimes|in:rent,sale,all',
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        if ($request->key_search != null && $request->activity == null) {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->whereStop('false')
                ->paginate(10);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->whereStop('false')
                ->paginate(10);
        } elseif ($request->key_search == null && $request->activity != null) {
            if ($request->activity == 'all')
            {
                $products = Product::with('provider')
                    ->whereHas('provider', function ($q) use ($request) {
                        $q->with('city');
                        $q->whereHas('city' , function ($d) use ($request){
                            $d->where('google_city_id', $request->google_city_id);
                        });
                    })
                    ->whereStop('false')
                    ->paginate(10);
                $providers = Provider::with('city')
                    ->whereHas('city' , function ($q) use ($request){
                        $q->where('google_city_id', $request->google_city_id);
                    })
                    ->whereStop('false')
                    ->paginate(10);
            }else{
                $products = Product::with('provider')
                    ->whereHas('provider', function ($q) use ($request) {
                        $q->with('city');
                        $q->whereHas('city' , function ($d) use ($request){
                            $d->where('google_city_id', $request->google_city_id);
                        });
                    })
                    ->where('activity', $request->activity)
                    ->whereStop('false')
                    ->paginate(10);
                $providers = Provider::with('city')
                    ->whereHas('city' , function ($q) use ($request){
                        $q->where('google_city_id', $request->google_city_id);
                    })
                    ->whereIn('activity', [$request->activity , 'both'])
                    ->whereStop('false')
                    ->paginate(10);
            }
        } elseif ($request->key_search != null && $request->activity != null) {
            if ($request->activity == 'all')
            {
                $products = Product::with('provider')
                    ->whereHas('provider', function ($q) use ($request) {
                        $q->with('city');
                        $q->whereHas('city' , function ($d) use ($request){
                            $d->where('google_city_id', $request->google_city_id);
                        });
                    })
                    ->where('name', 'LIKE', "%{$request->key_search}%")
                    ->whereStop('false')
                    ->paginate(15);
                $providers = Provider::with('city')
                    ->whereHas('city' , function ($q) use ($request){
                        $q->where('google_city_id', $request->google_city_id);
                    })
                    ->where('name', 'LIKE', "%{$request->key_search}%")
                    ->whereStop('false')
                    ->paginate(10);
            }else{
                $products = Product::with('provider')
                    ->whereHas('provider', function ($q) use ($request) {
                        $q->with('city');
                        $q->whereHas('city' , function ($d) use ($request){
                            $d->where('google_city_id', $request->google_city_id);
                        });
                    })
                    ->where('activity', $request->activity)
                    ->where('name', 'LIKE', "%{$request->key_search}%")
                    ->whereStop('false')
                    ->paginate(15);
                $providers = Provider::with('city')
                    ->whereHas('city' , function ($q) use ($request){
                        $q->where('google_city_id', $request->google_city_id);
                    })
                    ->whereIn('activity', [$request->activity , 'both'])
                    ->where('name', 'LIKE', "%{$request->key_search}%")
                    ->whereStop('false')
                    ->paginate(10);
            }
        } else {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })
                ->whereStop('false')
                ->paginate(10);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->whereStop('false')
                ->paginate(10);
        }
        if ($products->count() > 0 || $providers->count() > 0) {
            $data = [
                'providers' => new ProviderCollection($providers),
                'products' => new ProductCollection($products)
            ];
            return ApiController::respondWithSuccessData($data);
        } else {
            $errors = [
                'message' => ' لا يوجد منتجات ومزودين'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function home_screen(Request $request)
    {
        $rules = [
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        $products = Product::with('provider')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city', function ($d) use ($request) {
                    $d->where('google_city_id', $request->google_city_id);
                });
            })
            ->where('recomended' , 'true')
            ->whereStop('false')
            ->get();
        return ApiController::respondWithSuccessData(new HomeResource($products));
        // if ($products->count() > 0) {
        //     return ApiController::respondWithSuccessData(new HomeResource($products));
        // } else {
        //     $errors = [
        //         'message' => 'لا توجد منتجات'
        //     ];
        //     return ApiController::respondWithErrorClient($errors);
        // }
    }

    public function product_details($id)
    {
        $product = Product::whereId($id)->whereStop('false')->first();
        if ($product) {
            $products = Product::whereProviderId($product->provider_id)
//                ->with('provider')
//                ->whereHas('provider', function ($q) use ($product) {
//                    $q->where('category_id', $product->provider->category_id);
//                })
                ->where('id' , '!=' , $id)
                ->whereStop('false')
                ->orderBy('id' , 'desc')
                ->get()->take(10);
            $data = [
                'product_details' => new ProductResource($product),
                'similar_products' => ProductResource::collection($products),
                'contact_number' => Setting::first()->contact_number,
                'whatsaaAppMessage' => Setting::first()->contact_text,
            ];
            return ApiController::respondWithSuccessData($data);
        } else {
            $errors = [
                'message' => ' لا يوجد هذا المنتج'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
