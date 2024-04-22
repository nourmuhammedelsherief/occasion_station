<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProviderCommissionHistory;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:provider');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  DB::table('products')->where('provider_id' , Auth::guard('provider')->user()->id)->count();
        $orders_notPaid = Order::whereProviderId(\auth()->guard('provider')->user()->id)
            ->where('status' , 'new_no_paid')
            ->count();
        $orders_paid = Order::whereProviderId(\auth()->guard('provider')->user()->id)
            ->where('status' , 'new_paid')
            ->count();
        $orders_works = Order::whereProviderId(\auth()->guard('provider')->user()->id)
            ->where('status' , 'works_on')
            ->count();
        $orders_completed = Order::whereProviderId(\auth()->guard('provider')->user()->id)
            ->where('status' , 'completed')
            ->count();
        $orders_canceled = Order::whereProviderId(\auth()->guard('provider')->user()->id)
            ->where('status' , 'canceled')
            ->count();
        $commissions = ProviderCommissionHistory::whereProviderId(Auth::guard('provider')->user()->id)->count();

        return view('provider.home' , compact('products','orders_canceled','orders_completed','orders_notPaid','orders_paid','orders_works','commissions' ));
    }
    public function get_regions($id)
    {
        $regions = City::where('parent_id',$id)->select('id','name')->get();
        $data['regions']= $regions;
        return json_encode($data);
    }
    public function public_notifications()
    {
        return view('admin.public_notifications');
    }
    public function store_public_notifications(Request $request)
    {
        $this->validate($request , [
            "type"       => "required|in:1,2",
            "ar_title"   => "required",
            "en_title"   => "required",
            "ur_title"   => "required",
            "ar_message" => "required",
            "en_message" => "required",
            "ur_message" => "required",
        ]);
        // Create New Notification

        $users = User::whereType($request->type)->where('active' , '1')->get();
        foreach ($users as $user)
        {
            $ar_title = $request->ar_title;
            $en_title = $request->en_title;
            $ur_title = $request->ur_title;
            $ar_message = $request->ar_message;
            $en_message = $request->en_message;
            $ur_message = $request->ur_message;
            $devicesTokens =  UserDevice::where('user_id',$user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification($ar_title, $ar_message ,$devicesTokens);
            }
            saveNotification($user->id, $ar_title,$en_title,$ur_title, $ar_message ,$en_message,$ur_message,'0' , null , null);

        }
        flash('تم ارسال الاشعار بنجاح')->success();


        return redirect()->route('public_notifications');

    }
    public function user_notifications()
    {
        return view('admin.user_notification');
    }
    public function store_user_notifications(Request $request)
    {
        $this->validate($request, [
            'user_id*'   => 'required',
            "ar_title"   => "required",
            "en_title"   => "required",
            "ur_title"   => "required",
            "ar_message" => "required",
            "en_message" => "required",
            "ur_message" => "required",
        ]);
        foreach ($request->user_id as $one) {
            $user = User::find($one);
            $ar_title = $request->ar_title;
            $en_title = $request->en_title;
            $ur_title = $request->ur_title;
            $ar_message = $request->ar_message;
            $en_message = $request->en_message;
            $ur_message = $request->ur_message;
            $devicesTokens =  UserDevice::where('user_id',$user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification($ar_title, $ar_message ,$devicesTokens);
            }
            saveNotification($user->id, $ar_title,$en_title,$ur_title, $ar_message ,$en_message,$ur_message,'0' , null , null);
        }
        flash('تم ارسال الاشعار للمستخدمين بنجاح')->success();
        return redirect()->route('user_notifications');
    }
    public function orders($status)
    {
        $orders = Order::whereStatus($status)->get();
        if($status == '0')
        {
            return view('admin.orders.new' , compact('orders'));
        }elseif ($status == '1')
        {
            return view('admin.orders.active' , compact('orders'));
        }elseif ($status == '2')
        {
            return view('admin.orders.finished' , compact('orders'));
        }elseif ($status == '3')
        {
            return view('admin.orders.canceled' , compact('orders'));
        }
    }
}
