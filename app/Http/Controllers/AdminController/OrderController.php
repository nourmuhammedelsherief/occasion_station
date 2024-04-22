<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Admin;
use App\Models\AdminOrder;
use App\Models\AdminOrderHistory;
use App\Models\Cart;
use App\Models\Notification;
use App\Notifications\NewAdminNotification;
use App\Models\Order;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * @get New Not Paid Orders   -> Bank Transfers
     * @new_not_paid_orders
     */
    public function new_not_paid_orders()
    {
        $orders = Cart::where('payment_type', 'bank_transfer')
            ->where('status', 'new_no_paid')
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        return view('admin.orders.not_paid', compact('orders'));
    }

    public function PaymentDone($id)
    {
        $order = Cart::findOrFail($id);
        if ($order->transfer_photo != null) {
            @unlink(public_path('/uploads/transfers/' . $order->transfer_photo));
        }
        $order->update([
            'transfer_photo' => null,
            'status' => 'new_paid',
        ]);
        if ($order->orders->count() > 0) {
            foreach ($order->orders as $item) {
                $item->update([
                    'status' => 'new_paid',
                ]);
                if($item->product->delivery == 'yes')
                {
                    $note = $item->delivery_date . ' ' . $item->delivery_time;
                    $obj = array(
                        'sender_data' => array(
                            'address_type' => "business",
                            'name' => $item->provider->name,
                            'email' => $item->provider->email,
                            'apartment' => "",
                            'building' => "",
                            'street' => $item->provider->address,
                            'landmark' => "",
                            'city' => array(
                                'code' => $item->provider->city->code,
                                'lat' => $item->provider->latitude,
                                'lon' => $item->provider->longitude,
                            ),
                            'country' => array(
                                'id' => 191
                            ),
                            'phone' => $item->provider->phone_number,
                        ),
                        'recipient_data' => array(
                            'address_type' => "business",
                            'name' => $item->user->name,
                            'email' => $item->user->email == null ? "recipient@example.com" : $item->user->email,
                            'apartment' => "",
                            'building' => "",
                            'street' => $item->delivery_address,
                            'landmark' => "",
                            'city' => array(
                                'id' => "26148057",
                                'lat' => $order->delivery_latitude,
                                'lon' => $order->delivery_longitude,
                            ),
                            'country' => array(
                                'id' => 191
                            ),
                            'phone' => $item->user->phone_number,
                        ),
                        'dimensions' => array(
                            "weight" => 1,
                            "width" => 10,
                            "length" => 10,
                            "height" => 10,
                            "unit" => "METRIC",
                            "domestic" => false
                        ),
                        'package_type' => array(
                            "courier_type" => "B_2_B"
                        ),
                        'charge_items' => array(
                            array(
                                "charge_type" => "cod",
                                "charge" => Setting::find(1)->delivery_price,
                                "payer" => "recipient"
                            ),
//            array(
//                "charge_type" => "service_custom",
//                "charge" => 0,
//                "payer" => "recipient"
//            )
                        ),
                        "recipient_not_available" => "do_not_deliver",
                        "payment_type" => "credit_balance",
                        "payer" => "recipient",
                        "parcel_value" => 145,
                        "fragile" => true,
                        "note" => $note,
                        "piece_count" => "",
                        "force_create" => true,
                        "reference_id" => $item->id,
                    );
                    createColdtOrder($obj);
                }
            }
            // send order to coldt
//            dd($item->provider->city->code);


        }
        $admin = Admin::where('admin_category_id', 5)->inRandomOrder()->first();
        $admin->notify(new NewAdminNotification($order->id));
        // create new Admin Order
        AdminOrder::create([
            'admin_id' => $admin->id,
            'order_id' => $order->id,
        ]);
        // record the operation at history
        AdminOrderHistory::create([
            'admin_id' => auth()->guard('admin')->user()->id,
            'order_id' => $order->id,
            'notes' => null,
            'title' => 'تم تأكيد عمليه الدفع'
        ]);
        $title = 'الطلبات';
        $message = 'تم قبول عملية الدفع عن طريق التحويل البنكي بنجاح';
        $devicesTokens =  UserDevice::where('user_id',$order->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
        if ($devicesTokens) {
            sendNotification($devicesTokens , $title, $message , null);
        }
        saveNotification($order->user_id, $title, $message ,'3' ,null , null);

        $title = 'الطلبات';
        $message = 'نشكر لكم تسوقكم وتم أستلام طلبك';
        $devicesTokens =  UserDevice::where('user_id',$order->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
        if ($devicesTokens) {
            sendNotification($devicesTokens , $title, $message , null);
        }
        saveNotification($order->user_id, $title, $message ,'3' ,$order->id , null);
        flash('تم عمليه التأكيد بنجاح')->success();
        return redirect()->back();
    }

    public function CancelOrder($id)
    {
        $order = Cart::findOrFail($id);
        if ($order->transfer_photo != null) {
            @unlink(public_path('/uploads/transfers/' . $order->transfer_photo));
        }
        $order->update([
            'transfer_photo' => null,
            'status' => 'canceled',
        ]);
        if ($order->orders->count() > 0) {
            foreach ($order->orders as $item) {
                $item->update([
                    'status' => 'canceled',
                ]);
            }
        }

        // record the operation at history
        AdminOrderHistory::create([
            'admin_id' => \auth()->guard('admin')->user()->id,
            'order_id' => $order->id,
            'notes' => null,
            'title' => 'تم الغاء الطلب'
        ]);
        flash('تم الغاء الطلب بنجاح')->success();
        return redirect()->back();
    }

    public function showOrder($id, $notify_id = null)
    {
        $order = Cart::findOrFail($id);
        if ($notify_id != null) {
            auth()->guard('admin')->user()->unreadNotifications->where('id', $notify_id)->markAsRead();
        }
        $admins = Admin::where('admin_category_id', 5)
            ->where('id', '!=', Auth::guard('admin')->user()->id)
            ->get();
        $notes = AdminOrderHistory::whereAdminId(\auth()->guard('admin')->user()->id)
            ->where('notes', '!=', null)
            ->where('order_id', $order->id)
            ->get();
        return view('admin.orders.show', compact('order', 'admins', 'notes'));
    }

    /**
     * @get Admin Orders
     * @orders
     */
    public function orders($status = null)
    {
        $admin = Auth::guard('admin')->user();
        if ($status == null) {
            if ($admin != null && $admin->admin_category_id == 4) {
                // the main Admin
                $orders = Cart::whereNotIn('status', ['opened', 'sent', 'on_cart', 'new_no_paid'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(100);
            } elseif ($admin != null && $admin->admin_category_id == 5) {
                // Sales Admins
                $orders = Cart::with('admin_orders')
                    ->whereHas('admin_orders', function ($q) use ($admin) {
                        $q->where('admin_id', $admin->id);
                    })
                    ->whereNotIn('status', ['opened', 'sent', 'on_cart', 'new_no_paid'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(100);
            }
        } else {
            if ($admin != null && $admin->admin_category_id == 4) {
                // the main Admin
                $orders = Cart::whereStatus($status)->paginate(100);
            } elseif ($admin != null && $admin->admin_category_id == 5) {
                // Sales Admins
                $orders = Cart::with('admin_orders')
                    ->whereHas('admin_orders', function ($q) use ($admin) {
                        $q->where('admin_id', $admin->id);
                    })
                    ->where('status', $status)
                    ->orderBy('created_at', 'desc')
                    ->paginate(100);
            }
        }
        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function change_status(Request $request, $id)
    {
        $order = Cart::findOrFail($id);
        $this->validate($request, [
            'status' => 'required'
        ]);
        $order->update([
            'status' => $request->status,
        ]);
        if ($order->orders->count() > 0) {
            foreach ($order->orders as $item) {
                if ($request->status == 'completed') {
                    // calculate the commission for provider
                    $commission_value = Setting::find(1)->commission;
                    $commission = ($item->order_price * $commission_value) / 100;
                    $item->update([
                        'status' => $request->status,
                        'commission' => $commission,
                    ]);
                    $provider_total_commission = $item->provider->commission + $commission;
                    $item->provider->update([
                        'commission' => $provider_total_commission,
                    ]);
                } else {
                    $item->update([
                        'status' => $request->status,
                    ]);
                }
            }
        }

        // Send Notification To User
        $devicesTokens = UserDevice::where('user_id', $order->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
        $status = $request->status;
        if ($request->status == 'new_paid') {
            $status = 'جديد مدفوع';
        } elseif ($request->status == 'works_on') {
            $status = 'جاري العمل عليه';
        } elseif ($request->status == 'completed') {
            $status = 'مكتمل';
        } elseif ($request->status == 'canceled') {
            $status = 'ملغي';
        }

        $message = 'تم تغيير حاله الطلب الي ' . $status;
        $title = trans('messages.orders');
        if ($devicesTokens) {
            sendNotification($devicesTokens , $title, $message , null);
        }
        saveNotification($order->user_id, $title, $message, '0', $order->id);

        // record the operation at history
        AdminOrderHistory::create([
            'admin_id' => auth()->guard('admin')->user()->id,
            'order_id' => $order->id,
            'notes' => null,
            'title' => $message
        ]);

        flash('تم تغيير حاله الطلب بنجاح')->success();
        return redirect()->back();
    }

    public function redirection(Request $request, $id)
    {
        $order = Cart::findOrFail($id);
        $this->validate($request, [
            'admin_id' => 'required'
        ]);
        $check_order = AdminOrder::whereOrderId($order->id)->delete();
        $admin = Admin::findOrFail($request->admin_id);
        $admin->notify(new NewAdminNotification($order->id));
        // create new Admin Order
        AdminOrder::create([
            'admin_id' => $admin->id,
            'order_id' => $order->id,
        ]);

        // record the operation at history
        AdminOrderHistory::create([
            'admin_id' => \auth()->guard('admin')->user()->id,
            'order_id' => $order->id,
            'notes' => null,
            'title' => 'تم توجيه الطلب الي ',
            'redirection_admin' => $admin->id,
        ]);
        flash('تم توجية الطلب  الي الموظف بنجاح')->success();
        return redirect()->back();
    }

    /**
     *  Admin add His Note About Order
     * @AdminAddNote
     */
    public function AdminAddNote($id)
    {
        $order = Cart::findOrFail($id);
        return view('admin.orders.add_note', compact('order'));
    }

    public function storeAdminNote(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $order = Cart::findOrFail($id);
        $this->validate($request, [
            'notes' => 'required|string',
        ]);

        // create new Admin Note
        AdminOrderHistory::create([
            'admin_id' => \auth()->guard('admin')->user()->id,
            'order_id' => $order->id,
            'notes' => $request->notes,
            'title' => 'تسجيل ملاحظه علي الطلب',
            'redirection_admin' => null,
        ]);
        flash('تم تسجيل ملاحظاتك  علي  الطلب بنجاح')->success();
        return redirect()->route('showOrder', $order->id);
    }

    /**
     * @show Order @history
     * @orderHistory
     */
    public function orderHistory($id)
    {
        $order = Cart::findOrFail($id);
        $notes = AdminOrderHistory::whereOrderId($order->id)->get();
        return view('admin.orders.history', compact('notes', 'order'));
    }

    public function orders_completed($id)
    {
        $provider = Provider::findOrFail($id);
        $orders = Order::whereProviderId($provider->id)
            ->where('status' , 'completed')
            ->paginate(100);
        $status = 'completed';
        return view('admin.orders.index', compact('orders', 'status'));
    }
}
