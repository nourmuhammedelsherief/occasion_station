<?php

namespace App\Http\Controllers\ProviderController;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * @get @provider Orders
     * @index
     *
    */
    public function index($status = null)
    {
        $provider = auth()->guard('provider')->user();
        if ($status == null)
        {
            $orders = Cart::whereProviderId($provider->id)
                ->whereNotIn('status', ['opened', 'sent'])
                ->orderBy('created_at', 'desc')
                ->paginate(100);
        }elseif ($status != null)
        {
            $orders = Cart::whereProviderId($provider->id)
                ->where('status' , $status)
                ->orderBy('created_at', 'desc')
                ->paginate(100);
        }
        return view('provider.orders.index' , compact('orders','status'));
    }
    public function show($id , $notify_id = null)
    {
        $order = Cart::findOrFail($id);
        if ($notify_id != null)
        {
            auth()->guard('provider')->user()->unreadNotifications->where('id' , $notify_id)->markAsRead();
        }
        return view('provider.orders.show' , compact('order'));
    }
}
