<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationCollection;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use App\Models\ProviderRegister;
use App;
use Auth;

class ProviderRegisterController extends Controller
{
    public function provider_register_request(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:191',
            'store_name' => 'sometimes|string|max:191',
            'phone_number' => 'required|string|max:191',
            'email' => 'sometimes|string|max:191',
            'street' => 'sometimes|string|max:191',
            'district' => 'sometimes|string|max:191',
            'url' => 'sometimes|string|max:191',
            'city' => 'sometimes|string|max:191',
            'activity_type' => 'sometimes|string|max:191',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        // create new provider
        $provider = ProviderRegister::create([
            'name'             => $request->name,
            'phone_number'     => $request->phone_number,
            'store_name'       => $request->store_name,
            'city'             => $request->city,
            'district'         => $request->district,
            'street'           => $request->street,
            'activity_type'    => $request->activity_type,
            'email'            => $request->email,
            'url'              => $request->url,
            'status'           => 'new',
        ]);
        $success = [
            'message' => 'تم إرسال طلبك الي ألإدارة بنجاح',
        ];
        return ApiController::respondWithSuccessData($success);
    }
}
