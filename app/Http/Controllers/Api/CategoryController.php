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
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'sometimes|exists:sub_categories,id',
            'search' => 'sometimes',
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        if ($request->sub_category_id != null && $request->search != null) {
            $providers = Provider::with('provider_categories' ,'city')
                ->join('providers_main_categories' , 'providers_main_categories.provider_id' , '=', 'providers.id')
                ->whereHas('provider_categories', function ($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                })
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->search}%")
                ->where('providers_main_categories.category_id' , $request->category_id)
                ->whereStop('false')
                ->orderBy(DB::raw('ISNULL(providers_main_categories.arrange), providers_main_categories.arrange'), 'ASC')
                ->paginate(50);
        } elseif ($request->sub_category_id == null && $request->search != null) {
            $providers = Provider::with('city')
                ->join('providers_main_categories' , 'providers_main_categories.provider_id' , '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->search}%")
                ->where('providers_main_categories.category_id' , $request->category_id)
                ->whereStop('false')
                ->orderBy(DB::raw('ISNULL(providers_main_categories.arrange), providers_main_categories.arrange'), 'ASC')
                ->paginate(50);
        } elseif ($request->sub_category_id != null && $request->search == null) {
            $providers = Provider::with('provider_categories', 'city')
                ->join('providers_main_categories' , 'providers_main_categories.provider_id' , '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->whereHas('provider_categories', function ($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                })
                ->where('providers_main_categories.category_id' , $request->category_id)
                ->whereStop('false')
                ->orderBy(DB::raw('ISNULL(providers_main_categories.arrange), providers_main_categories.arrange'), 'ASC')
                ->paginate(50);
        } else {
            $providers = Provider::with('city')
                ->join('providers_main_categories' , 'providers_main_categories.provider_id' , '=', 'providers.id')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('providers_main_categories.category_id' , $request->category_id)
                ->whereStop('false')
                ->orderBy(DB::raw('ISNULL(providers_main_categories.arrange), providers_main_categories.arrange'), 'ASC')
                ->paginate(50);
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
