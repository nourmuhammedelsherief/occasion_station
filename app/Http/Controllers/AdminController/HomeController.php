<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Cart;
use App\Models\Category;
use App\Models\City;
use App\Models\ContactUs;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = DB::table('admins')->count();
        $users =  DB::table('users')->count();
        $main_categories = Category::count();
        $sub_categories = SubCategory::count();
        $cities = City::count();
        $products = Product::count();
        $providers = Provider::count();
        $contacts = ContactUs::count();
        $sliders = Slider::count();
        $orders_wait_pay = Cart::where('payment_type' , 'bank_transfer')
            ->where('status' , 'new_no_paid')->count();
        if (auth()->guard('admin')->user()->admin_category_id == 4)
        {
            $paid_orders = Cart::whereStatus('new_paid')->count();
            $works_orders = Cart::whereStatus('works_on')->count();
            $completed_orders = Cart::whereStatus('completed')->count();
            $canceled_orders = Cart::whereStatus('canceled')->count();
        }elseif (auth()->guard('admin')->user()->admin_category_id == 5)
        {
            $paid_orders = Cart::with('admin_orders')
                ->whereHas('admin_orders' , function ($q){
                    $q->where('admin_id' , auth()->guard('admin')->user()->id);
                })
                ->where('status' , 'new_paid')
                ->count();
            $works_orders = Cart::with('admin_orders')
                ->whereHas('admin_orders' , function ($q){
                    $q->where('admin_id' , auth()->guard('admin')->user()->id);
                })
                ->where('status' , 'works_on')
                ->count();
            $completed_orders = Cart::with('admin_orders')
                ->whereHas('admin_orders' , function ($q){
                    $q->where('admin_id' , auth()->guard('admin')->user()->id);
                })
                ->where('status' , 'completed')
                ->count();
            $canceled_orders = Cart::with('admin_orders')
                ->whereHas('admin_orders' , function ($q){
                    $q->where('admin_id' , auth()->guard('admin')->user()->id);
                })
                ->where('status' , 'canceled')
                ->count();
        }
        if (auth()->guard('admin')->user()->admin_category_id == 4)
        {
            return view('admin.home' , compact('sliders','contacts','paid_orders','works_orders','completed_orders','canceled_orders','orders_wait_pay','products','providers','cities','main_categories','sub_categories','admins' ,'users' ));
        }elseif (auth()->guard('admin')->user()->admin_category_id == 5)
        {
            return view('admin.home' , compact('paid_orders','works_orders','completed_orders','canceled_orders'));
        }elseif (auth()->guard('admin')->user()->admin_category_id == 3)
        {
            return view('admin.home' , compact('orders_wait_pay','providers'));
        }elseif (auth()->guard('admin')->user()->admin_category_id == 6)
        {
            return view('admin.home' , compact('contacts'));
        }
    }
    public function get_regions($id)
    {
        $regions = City::where('parent_id',$id)->select('id','name')->get();
        $data['regions']= $regions;
        return json_encode($data);
    }
    public function public_notifications()
    {
        return view('admin.notifications.public_notifications');
    }
    public function store_public_notifications(Request $request)
    {
        $this->validate($request , [
            "title"   => "required|string|max:191",
            "message" => "required|string",
        ]);
        $title = $request->title;
        $message = $request->message;
        // Create New Notification
        // $users = User::where('active' , 'active')->get();
        // foreach ($users as $user)
        // {
        //     $title = $request->title;
        //     $message = $request->message;
        //     $devicesTokens =  UserDevice::where('user_id',$user->id)
        //         ->get()
        //         ->pluck('device_token')
        //         ->toArray();
        //     if ($devicesTokens) {
        //         sendMultiNotification($title, $message ,$devicesTokens);
        //     }
        //     saveNotification($user->id, $title, $message ,'3' , null , null);

        // }
        // send Notification to visitors
        $visitors = UserDevice::where('device_token' , '!=' , null)
            ->whereNotIn('device_token' , ['nabil' , 'TEST_TOKEN'])
            ->distinct()
            ->get();
        foreach ($visitors as $visitor) {
            sendNotification($visitor->device_token , $title, $message , null);
            saveNotification($visitor->user_id , $title , $message, '3'   , null, $visitor->device_token , null , null);
        }
        flash('تم ارسال الاشعار بنجاح')->success();
        return redirect()->route('public_notifications');

    }
    public function user_notifications()
    {
        $users = User::where('active' , 'active')->get();
        return view('admin.notifications.user_notification' , compact('users'));
    }
    public function store_user_notifications(Request $request)
    {
        $this->validate($request, [
            'user_id*'   => 'required',
            "title"   => "required",
            "message" => "required",
        ]);
        foreach ($request->user_id as $one) {
            $user = User::find($one);
            $title = $request->title;
            $message = $request->message;
            $devicesTokens =  UserDevice::where('user_id',$user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendNotification($devicesTokens , $title, $message , null);
            }
            saveNotification($user->id , $title , $message, '3'   , null, null , null , null);
        }
        flash('تم إرسال الإشعار للمستخدمين بنجاح')->success();
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
    public function specified_notification()
    {
        return view('admin.notifications.specified_notification');
    }
    public function store_specified_notification(Request $request)
    {
        $this->validate($request , [
            'type'    => 'required|in:users,providers',
            "title"   => "required|string|max:191",
            "message" => "required|string",
        ]);

        $title = $request->title;
        $message = $request->message;
        if ($request->type == 'users')
        {
            // send Notification to visitors
            $visitors = UserDevice::where('device_token' , '!=' , null)
                ->whereNotIn('device_token' , ['nabil' , 'TEST_TOKEN'])
                ->distinct()
                ->get();
            foreach ($visitors as $visitor) {
                sendMultiNotification($title, $message ,$visitor->device_token);
                saveNotification($visitor->user_id, $title, $message ,'3' , null , $visitor->device_token);

            }
        }elseif ($request->type == 'providers')
        {
            $providers = Provider::all();
            foreach ($providers as $provider)
            {
                $devicesTokens =  UserDevice::where('user_id',$provider->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                if ($devicesTokens) {
                    sendMultiNotification($title, $message ,$devicesTokens);
                }
                saveNotification($provider->id, $title, $message ,'3' , null , null);

            }
        }

        flash('تم إرسال الإشعار الي الفئة المحددة بنجاح')->success();

        return redirect()->route('specified_notification');
    }
}
