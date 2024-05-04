<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProviderCollectionTest;
use App\Models\FavoriteProduct;
use App\Models\FavoriteProvider;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Http\Request;
use Validator;


class FavoriteController extends Controller
{
    /**
     *  Add Provider To Favorite
     *  @add_provider_to_favorite
     * @provider_id int
     * @user_id int
    */
    public function add_provider_to_favorite(Request $request)
    {
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'favorite'    => 'required|in:true,false'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = $request->user();
        $provider = Provider::find($request->provider_id);
        $check_fav = FavoriteProvider::whereUserId($user->id)
            ->whereProviderId($provider->id)
            ->first();
        if ($request->favorite  == 'true')
        {
            if ($check_fav)
            {
                $errors = [
                    'message' => trans('messages.favorite_before')
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            else{
                // add provider to favorites
                FavoriteProvider::create([
                    'user_id'  => $user->id,
                    'provider_id' => $provider->id,
                ]);
                $success = [
                    'message' => trans('messages.favorite_successfully'),
                ];
                return ApiController::respondWithSuccessData($success);
            }
        }elseif ($request->favorite == 'false')
        {
            if ($check_fav == null)
            {
                $errors = [
                    'message' => trans('messages.not_favorite_before')
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            else{
                $check_fav->delete();
                $success = [
                    'message' => trans('messages.favorite_removed_successfully'),
                ];
                return ApiController::respondWithSuccessData($success);
            }
        }
    }
    /**
     *  Add Provider To Favorite
     *  @add_product_to_favorite
     * @product_id int
     * @user_id int
     */
    public function add_product_to_favorite(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'favorite'    => 'required|in:true,false'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = $request->user();
        $product = Product::find($request->product_id);
        $check_fav = FavoriteProduct::whereUserId($user->id)
            ->whereProductId($product->id)
            ->first();
        if ($request->favorite  == 'true')
        {
            if ($check_fav)
            {
                $errors = [
                    'message' => trans('messages.favorite_before')
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            else{
                // add provider to favorites
                FavoriteProduct::create([
                    'user_id'  => $user->id,
                    'product_id' => $product->id,
                ]);
                $success = [
                    'message' => trans('messages.favorite_successfully'),
                ];
                return ApiController::respondWithSuccessData($success);
            }
        }elseif ($request->favorite == 'false')
        {
            if ($check_fav == null)
            {
                $errors = [
                    'message' => trans('messages.not_favorite_before')
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            else{
                $check_fav->delete();
                $success = [
                    'message' => trans('messages.favorite_removed_successfully'),
                ];
                return ApiController::respondWithSuccessData($success);
            }
        }
    }

    public function my_favorite_providers(Request $request)
    {
        $user = $request->user();
        $favorite_providers = Provider::with('favorites')
            ->whereHas('favorites' , function ($q) use ($user){
                $q->whereUserId($user->id);
            })->paginate(10);

        if ($favorite_providers->count() > 0) {
            return ApiController::respondWithSuccessData(new ProviderCollectionTest($favorite_providers));
        } else {
            $errors = [
                'message' => trans('messages.no_providers_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function my_favorite_products(Request $request)
    {
        $user = $request->user();
        $favorite_products = Product::with('favorites')
            ->whereHas('favorites' , function ($q) use ($user){
                $q->whereUserId($user->id);
            })->paginate(10);

        if ($favorite_products->count() > 0) {
            return ApiController::respondWithSuccessData(new ProductCollection($favorite_products));
        } else {
            $errors = [
                'message' => trans('messages.no_products_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

}
