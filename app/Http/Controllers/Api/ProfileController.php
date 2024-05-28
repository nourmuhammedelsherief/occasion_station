<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationCollection;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\Models\User;
use App\Models\AboutUs;
use App\Models\Setting;
use App\Models\Bank;
use App\Models\AnimatedSlider;
use App\Models\TermsCondition;
use App\Models\ContactUs;
use App\Models\Provider;
use App\Models\ProviderSlider;
use App\Models\ProductCategory;
use App;
use Auth;

class ProfileController extends Controller
{
    //
    public function about_us(Request $request)
    {
        $about = AboutUs::first();
        $all = [
            'title' => $request->header('Accept-Language') == 'ar' ? $about->title : $about->title_en,
            'content' => $request->header('Accept-Language') == 'ar' ? $about->content : $about->content_en,
        ];
        return ApiController::respondWithSuccess($all);
    }

    public function contact_number()
    {
        $number = Setting::first();
        $all = [
            'contact_number' => $number->contact_number,
            'whatsaaAppMessage' => $number->contact_text,
        ];
        return ApiController::respondWithSuccess($all);
    }
    public function sliders()
    {
        $sliders = AnimatedSlider::all();
        if ($sliders->count() > 0)
        {
            $data = [
                'data' =>App\Http\Resources\AnimatedSliderResource::collection($sliders),
            ];
            return ApiController::respondWithSuccess($data);
        }else{
            $errors = [
                'message' => 'لا توجد صور'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function advisor_contact_number()
    {
        $number = Setting::first()->advisor_number;
        $all = [
            'advisor_contact_number' => $number,
        ];
        return ApiController::respondWithSuccess($all);
    }
    public function customer_services_number()
    {
        $number = Setting::first()->customer_services_number;
        $all = [
            'customer_services_number' => $number,
        ];
        return ApiController::respondWithSuccess($all);
    }


    public function terms_and_conditions(Request $request)
    {
        $terms = TermsCondition::first();
        $all = [
            'title' => $request->header('Accept-Language') == 'ar' ? $terms->title : $terms->title_en,
            'content' => $request->header('Accept-Language') == 'ar' ? $terms->content : $terms->content_en,
        ];
        return ApiController::respondWithSuccess($all);
    }

    public function banks()
    {
        $banks = Bank::all();
        if ($banks->count() > 0) {
            return ApiController::respondWithSuccessData(App\Http\Resources\BankResource::collection($banks));
        }
    }

    public function contact_us(Request $request)
    {
        $rules = [
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new contact
        $contact = ContactUs::create([
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
            'phone_number' => $request->user()->phone_number,
            'message' => $request->message,
        ]);
        $success = [
            'message' => 'تم أرسال  الرساله الي  الأدراه بنجاح'
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function store_device_token(Request $request)
    {
        $rules = [
            'device_token' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $created = ApiController::createDeviceToken($request->device_token);

        $success = [
            'device_token' => $created->device_token
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function get_visitors_notifications($id)
    {
        $notifications = App\Models\UserNotification::Where('device_token', $id)
            ->orderBy('id','desc')
            ->get();
        if ($notifications->count() > 0)
        {
            return ApiController::respondWithSuccessData(new NotificationCollection($notifications));
        }else{
            $errors = [
                'message'  => trans('messages.no_notifications')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

    }
    public function provider_sliders($id)
    {
        $provider = Provider::find($id);
        if ($provider)
        {
            $sliders = ProviderSlider::whereProviderId($provider->id)->get();
            if($sliders->count() == 0)
            {
                $sliders = ProviderSlider::where('provider_id' , null)->get();
                foreach ($sliders  as $slider)
                {
                    ProviderSlider::create([
                        'provider_id' => $provider->id,
                        'photo'       => $slider->photo,
                    ]);
                }
            }
            $sliders = ProviderSlider::whereProviderId($provider->id)->get();
            $listSliders = ['listSlider' => App\Http\Resources\ProviderSliderResource::collection($sliders)];
            $success = [
                'data' => $listSliders,
            ];
            return ApiController::respondWithSuccess($success);
        }
    }
    public function provider_categories()
    {
        $categories = ProductCategory::all();
        return ApiController::respondWithSuccess(App\Http\Resources\ProviderCategoryResource::collection($categories));
    }
    public function tamara_pre_check()
    {
        $success = [
            'data' => tamara(),
        ];
        return ApiController::respondWithSuccess($success);
    }

}
