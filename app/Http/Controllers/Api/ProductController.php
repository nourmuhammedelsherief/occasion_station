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

        $products = Product::whereProviderId($request->provider_id)
            ->where(function ($query) use ($request) {
                if (isset($request->key_search)) {
                    $query->where('name', 'LIKE', "%{$request->search}%");
                }
                if (isset($request->provider_category_id) and $request->provider_category_id != 'all') {
                    $query->where('category_id',$request->provider_category_id);
                }
                if (isset($request->activity) and $request->activity != 'all') {
                    $query->where('activity',$request->activity);
                }
            })
            ->whereStop('false')
            ->whereAccepted('true')
            ->orderBy('id' , 'desc')
            ->paginate(15);
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

    public function products_search(Request $request)
    {
        $rules = [
            'key_search' => 'sometimes|string|max:191',
            'activity'   => 'required|in:rent,sale,all',
            'google_city_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $products = Product::with('provider')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city' , function ($d) use ($request){
                    $d->where('google_city_id', $request->google_city_id);
                });
            })
            ->where(function ($query) use ($request) {
                if (isset($request->key_search)) {
                    $query->where('name', 'LIKE', "%{$request->key_search}%");
                }
                if (isset($request->activity) and $request->activity != 'all') {
                    $query->where('activity',$request->activity);
                }
            })
            ->whereStop('false')
            ->whereAccepted('true')
            ->paginate(15);
        if ($products->count() > 0 ) {
            return ApiController::respondWithSuccessData(new ProductCollection($products));
        } else {
            $errors = [
                'message' => trans('messages.no_products_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function home_screen(Request $request)
    {
        return ApiController::respondWithSuccessData(new HomeResource(1));
    }

    public function recommended_products(Request $request)
    {
        $rules = [
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $products = Product::with('provider')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city', function ($d) use ($request) {
                    $d->where('google_city_id', $request->google_city_id);
                });
            })
            ->where('recomended' , 'true')
            ->whereStop('false')
            ->whereAccepted('true')
            ->paginate(15);
        return ApiController::respondWithSuccessData(new ProductCollection($products));
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
                ->whereAccepted('true')
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
                'message' => trans('messages.not_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
