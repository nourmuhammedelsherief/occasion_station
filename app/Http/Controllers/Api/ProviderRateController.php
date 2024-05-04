<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProviderRateCollection;
use App\Models\Provider;
use App\Models\ProviderRate;
use Illuminate\Http\Request;
use Validator;

class ProviderRateController extends Controller
{
    public function rate_provider(Request $request)
    {
        $user = $request->user();
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'rate'        => 'required|in:1,2,3,4,5',
            'rate_text'   => 'nullable|string'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $provider = Provider::find($request->provider_id);
        ProviderRate::updateOrCreate([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
        ] , [
            'rate'         => $request->rate,
            'rate_text'    => $request->rate_text,
        ]);
        $provider->update([
            'rate'  => providerRateAvg($provider->id),
        ]);
        $success = [
            'message' => trans('messages.provider_rated_successfully'),
        ];
        return ApiController::respondWithSuccessData($success);
    }

    public function provider_rates($id)
    {
        $provider = Provider::find($id);
        $rates = $provider->rates()->paginate(10);
        return ApiController::respondWithSuccessData(new ProviderRateCollection($rates));

    }
}
