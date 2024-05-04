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
                'message' => 'لا يوجد أقسام'
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
                'message' => 'لا يوجد مدن'
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
                'message' => ' لا يوجد أقسام فرعية'
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
        if ($request->rate_order and $request->latitude) {
            $providers = Provider::with('provider_categories', 'city')
                ->join('providers_main_categories', 'providers_main_categories.provider_id', '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search)) {
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
                })
                ->where('providers_main_categories.category_id', $request->category_id)
                ->whereStop('false')
                ->orderBy('rate', 'DESC')
                ->orderBy(DB::raw("3959 * acos( cos( radians({$request->input('latitude')}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-{$request->input('longitude')}) ) + sin( radians({$request->input('latitude')}) ) * sin(radians(latitude)) )"), 'ASC')
                ->paginate(10);
        } elseif ($request->rate_order and $request->latitude == null) {
            $providers = Provider::with('provider_categories', 'city')
                ->join('providers_main_categories', 'providers_main_categories.provider_id', '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search)) {
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
                })
                ->where('providers_main_categories.category_id', $request->category_id)
                ->whereStop('false')
                ->orderBy('rate', 'DESC')
                ->paginate(10);
        } elseif ($request->latitude and $request->rate_order == null) {
            $providers = Provider::with('provider_categories', 'city')
                ->join('providers_main_categories', 'providers_main_categories.provider_id', '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search)) {
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
                })
                ->where('providers_main_categories.category_id', $request->category_id)
                ->whereStop('false')
                ->orderBy(DB::raw("3959 * acos( cos( radians({$request->input('latitude')}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-{$request->input('longitude')}) ) + sin( radians({$request->input('latitude')}) ) * sin(radians(latitude)) )"), 'ASC')
                ->paginate(10);
        } else {
            $providers = Provider::with('provider_categories', 'city')
                ->join('providers_main_categories', 'providers_main_categories.provider_id', '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where(function ($query) use ($request) {
                    if (isset($request->search)) {
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
                })
                ->where('providers_main_categories.category_id', $request->category_id)
                ->whereStop('false')
                ->paginate(10);
        }
        if ($providers->count() > 0) {
            return ApiController::respondWithSuccessData(new ProviderCollectionTest($providers));
        } else {
            $errors = [
                'message' => ' لا يوجد مزودين خدمات'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
