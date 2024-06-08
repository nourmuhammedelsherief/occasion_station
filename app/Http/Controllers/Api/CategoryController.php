<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\City;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderCollectionTest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class CategoryController extends Controller
{
    /**
     * get main categories
     * @categories
     */
    public function categories()
    {
        $categories = Category::orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')->get();
        if ($categories->count() > 0) {
            return ApiController::respondWithSuccessData(CategoryResource::collection($categories));
        } else {
            $errors = [
                'message' => trans('messages.no_categories')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function cities()
    {
        $cities = City::all();
        if ($cities->count() > 0) {
            return ApiController::respondWithSuccessData(CityResource::collection($cities));
        } else {
            $errors = [
                'message' => trans('messages.no_cities')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function sub_categories($id)
    {
        $sub_categories = SubCategory::whereCategoryId($id)->get();
        if ($sub_categories->count() > 0) {
            return ApiController::respondWithSuccessData(SubCategoryResource::collection($sub_categories));
        } else {
            $errors = [
                'message' => trans('messages.no_sub_categories')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function providers(Request $request)
    {
        /// order by distance
        /// order by rate
        /// order by tamara
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'google_city_id' => 'required',
            'sub_category_id' => 'sometimes|exists:sub_categories,id',
            'search' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
            'rate_order' => 'sometimes',
            'tamara_order' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
        if ($request->latitude and $request->longitude and $request->rate_order) {
            $providers = Provider::with('provider_categories', 'city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search) and $request->search != null or $request->search != 'null') {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    }
                    if (isset($request->tamara_order)) {
                        $query->where('tamara_payment', 'true');
                    }
                    if (isset($request->sub_category_id)) {
                        $query->whereHas('provider_categories', function ($q) use ($request) {
                            $q->where('sub_category_id', $request->sub_category_id);
                        });
                    }
                    $query->whereStop('false');
                    $query->whereCategoryId($request->category_id);
                })
                ->orderBy('rate', 'DESC')
                ->orderBy(DB::raw("3959 * acos( cos( radians({$request->input('latitude')}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-{$request->input('longitude')}) ) + sin( radians({$request->input('latitude')}) ) * sin(radians(latitude)) )"), 'ASC')
                ->orderBy('vip', 'ASC')
                ->orderBy('special', 'ASC')
                ->orderBy(DB::raw('ISNULL(provider_category_arrange), provider_category_arrange'), 'ASC')
                ->paginate(15);
        } elseif ($request->latitude and $request->longitude and $request->rate_order == null) {
            $providers = Provider::with('provider_categories', 'city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search) and $request->search != null or $request->search != 'null') {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    }
                    if (isset($request->tamara_order)) {
                        $query->where('tamara_payment', 'true');
                    }
                    if (isset($request->sub_category_id)) {
                        $query->whereHas('provider_categories', function ($q) use ($request) {
                            $q->where('sub_category_id', $request->sub_category_id);
                        });
                    }
                    $query->whereStop('false');
                    $query->whereCategoryId($request->category_id);
                })
                ->orderBy(DB::raw("3959 * acos( cos( radians({$request->input('latitude')}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-{$request->input('longitude')}) ) + sin( radians({$request->input('latitude')}) ) * sin(radians(latitude)) )"), 'ASC')
                ->orderBy('vip', 'ASC')
                ->orderBy('special', 'ASC')
                ->orderBy(DB::raw('ISNULL(provider_category_arrange), provider_category_arrange'), 'ASC')
                ->paginate(15);
        } elseif ($request->latitude == null and $request->rate_order) {
            $providers = Provider::with('provider_categories', 'city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search) and $request->search != null or $request->search != 'null') {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    }
                    if (isset($request->tamara_order)) {
                        $query->where('tamara_payment', 'true');
                    }
                    if (isset($request->sub_category_id)) {
                        $query->whereHas('provider_categories', function ($q) use ($request) {
                            $q->where('sub_category_id', $request->sub_category_id);
                        });
                    }
                    $query->whereStop('false');
                    $query->whereCategoryId($request->category_id);
                })
                ->orderBy('rate', 'DESC')
                ->orderBy('vip', 'ASC')
                ->orderBy('special', 'ASC')
                ->orderBy(DB::raw('ISNULL(provider_category_arrange), provider_category_arrange'), 'ASC')
                ->paginate(15);
        } else {
            $providers = Provider::with('provider_categories', 'city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search) and $request->search != null or $request->search != 'null') {
                        $query->where('name', 'LIKE', "%{$request->search}%");
                    }
                    if (isset($request->tamara_order)) {
                        $query->where('tamara_payment', 'true');
                    }
                    if (isset($request->sub_category_id)) {
                        $query->whereHas('provider_categories', function ($q) use ($request) {
                            $q->where('sub_category_id', $request->sub_category_id);
                        });
                    }
                    $query->whereStop('false');
                    $query->whereCategoryId($request->category_id);
                })
                ->orderBy('vip', 'ASC')
                ->orderBy('special', 'ASC')
                ->orderBy(DB::raw('ISNULL(provider_category_arrange), provider_category_arrange'), 'ASC')
                ->paginate();
        }
        if ($providers->count() > 0) {
            return ApiController::respondWithSuccessData(new ProviderCollectionTest($providers));
        } else {
            $errors = [
                'message' => trans('messages.no_providers')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function providers_search(Request $request)
    {
        $rules = [
            'google_city_id' => 'required',
            'key_search' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $providers = Provider::with('provider_categories', 'city')
            ->whereHas('city', function ($q) use ($request) {
                $q->where('google_city_id', $request->google_city_id);
            })
            ->where(function ($query) use ($request) {
                if (isset($request->key_search)) {
                    $query->where('name', 'LIKE', "%{$request->key_search}%");
                }
                $query->whereStop('false');
            })
            ->orderBy('vip', 'ASC')
            ->orderBy('special', 'ASC')
            ->orderBy(DB::raw('ISNULL(provider_category_arrange), provider_category_arrange'), 'ASC')
            ->paginate(15);
        if ($providers->count() > 0) {
            return ApiController::respondWithSuccessData(new ProviderCollectionTest($providers));
        } else {
            $errors = [
                'message' => trans('messages.no_providers')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
