<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\AdminOrder;
use App\Models\Cart;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Mail\NotifyMail;
use App\Models\OrderOption;
use App\Models\ProductOption;
use App\Models\ProductSize;
use App\Notification;
use App\Notifications\NewAdminNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use Mail;


class OrderController extends Controller
{
    public function add_to_cart(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable|exists:product_sizes,id',
            'product_count' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        // create new Order
        $product = Product::find($request->product_id);
        $provider = $product->provider;
        if ($product == null) {
            $errors = [
                'message' => trans('messages.not_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        } elseif ($product->less_amount > $request->product_count) {
            $errors = [
                'message' => trans('messages.less_amount') . $product->less_amount,
            ];
            return ApiController::respondWithErrorClient($errors);
        }
        // 1- check if there are an open cart for this user with this provider
        $check_provider_cart = Cart::whereUserId($request->user()->id)
            ->whereProviderId($provider->id)
            ->whereStatus('opened')
            ->orderBy('id', 'desc')
            ->first();
        if ($check_provider_cart) {
            // add order to lasted opened cart
            // create new order
            $order = Order::create([
                'cart_id' => $check_provider_cart->id,
                'provider_id' => $product->provider_id,
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'size_id' => $request->size_id ?: null,
                'status' => 'on_cart',
                'product_count' => $request->product_count,
            ]);
            $options_price = 0;
            // check options
            if ($request->options) {
                foreach (json_decode($request->options) as $option) {
                    // add options to order
                    $option_price = ProductOption::find($option->id)->price * $option->count;
                    OrderOption::create([
                        'order_id' => $order->id,
                        'option_id' => $option->id,
                        'option_count' => $option->count,
                        'price' => $option_price,
                    ]);
                    $options_price += $option_price;
                }
            }
            $order_price = ($request->size_id ? ProductSize::find($request->size_id)->price : $product->price) * $request->product_count;
            $tax = Setting::find(1)->tax;
            $order_tax = (($order_price + $options_price) * $tax) / 100;
            $total_tax = $order_tax;
            $order->update([
                'order_price' => $order_price,
                'options_price' => $options_price,
                'tax_value' => $order_tax,
            ]);
            $items_price = $check_provider_cart->items_price + $order_price + $options_price;
            $delivery_price = $check_provider_cart->delivery_price;            // same provider with same delivery value
            $cart_tax_value = $check_provider_cart->tax_value + $total_tax;
            $total_price = $items_price + $delivery_price + $cart_tax_value;
            $check_provider_cart->update([
                'items_price' => $items_price,
                'total_price' => $total_price,
                'tax_value' => $cart_tax_value,
            ]);
            $cart = $check_provider_cart;
        } else {
            // create new cart provider and new product order
            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'provider_id' => $provider->id,
                'transfer_photo' => null,
                'invoice_id' => null,
                'payment_status' => 'wait',
                'status' => 'opened',
            ]);

            $order = Order::create([
                'cart_id' => $cart->id,
                'provider_id' => $product->provider_id,
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'status' => 'on_cart',
                'size_id' => $request->size_id ?: null,
                'product_count' => $request->product_count,
            ]);
            $options_price = 0;
            // check options
            if ($request->options) {
                foreach (json_decode($request->options) as $option) {
                    // add options to order
                    $option_price = ProductOption::find($option->id)->price * $option->count;
                    OrderOption::create([
                        'order_id' => $order->id,
                        'option_id' => $option->id,
                        'option_count' => $option->count,
                        'price' => $option_price,
                    ]);
                    $options_price += $option_price;
                }
            }


            $order_price = ($request->size_id ? ProductSize::find($request->size_id)->price : $product->price) * $request->product_count;
            $tax = Setting::find(1)->tax;
            $total_order_price = $order_price + $options_price;
            $order_tax = ($total_order_price * $tax) / 100;
            $total_price = $total_order_price + $order_tax;
            $order->update([
                'order_price' => $order_price,
                'options_price' => $options_price,
                'tax_value' => $order_tax,
            ]);
            $cart->update([
                'items_price'  => $total_order_price,
                'tax_value'    => $order_tax,
                'total_price'  => $total_price,
            ]);

        }
        $success = [
            'message' => trans('messages.cartItemAdded'),
            'cart_id' => $cart->id,
        ];
        return ApiController::respondWithSuccessData($success);

    }

    public function cart_items(Request $request, $provider_id)
    {
        $provider = Provider::find($provider_id);
        if ($provider) {
            $cart = Cart::whereUserId($request->user()->id)
                ->whereProviderId($provider->id)
                ->orderBy('id', 'desc')
                ->whereStatus('opened')
                ->first();
            if ($cart and $cart->orders->count() > 0) {
                return ApiController::respondWithSuccessData(new CartResource($cart));
            } else {
                $errors = [
                    'message' => trans('messages.emptyCart')
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        } else {
            $errors = [
                'message' => trans('messages.providerNotFound')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function complete_cart_orders(Request $request)
    {
        $rules = [
            'cart_id' => 'required|exists:carts,id',
            'payment_type' => 'required|in:online,bank_transfer,tamara',
            'transfer_photo' => 'required_if:payment_type,bank_transfer|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'charge_id' => 'required_if:payment_type,online|in:2,6,11',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'delivery_latitude' => 'required',
            'delivery_longitude' => 'required',
            'delivery_address' => 'required|string',
            'more_details' => 'sometimes|string',
            'tamara_instalment' => 'required_if:payment_type,tamara|in:0,2,3,4',
            'store_receiving' => 'nullable|in:true,false',
//            'provider_id'     => 'required_if:store_receiving,true'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $cart = Cart::find($request->cart_id);
        if ($cart) {
            if ($cart->status != 'opened') {
                $success = [
                    'message' => trans('messages.emptyCart'),
                ];
                return ApiController::respondWithErrorClient($success);
            }
            if ($cart->orders->count() > 0) {
                // calculate delivery price if delivery true
                $provider = $cart->provider;
                if ($provider->delivery == 'true') {
                    $delivery_price = $provider->delivery_price ?: Setting::first()->delivery_price;
                } else {
                    $delivery_price = 0;
                }
                if ($request->store_receiving == 'true' and $provider->store_receiving == 'true' and $cart->store_receiving == 'false') {
                    $cart->update([
                        'store_receiving' => 'true',
                    ]);
                    $delivery_price = 0;
                }
                $tax = Setting::find(1)->tax;
                $delivery_tax = ($delivery_price * $tax) / 100;
                $total_tax = $delivery_tax + $cart->tax_value;
                $total_price = $cart->total_price + $delivery_price + $delivery_tax;
                if ($cart->delivery_price == null) {
                    $cart->update([
                        'delivery_price' => $delivery_price,
                        'total_price' => $total_price,
                        'tax_value' => $total_tax,
                        'store_receiving' => $request->store_receiving ?: 'false',
                    ]);
                }
                if ($request->payment_type == 'bank_transfer') {
                    $cart->update([
                        'status' => 'new_no_paid',
                        'payment_status' => 'done',
                        'payment_type' => 'bank_transfer',
                        'transfer_photo' => $request->file('transfer_photo') == null ? null : UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
                        'delivery_date' => $request->delivery_date,
                        'delivery_time' => $request->delivery_time,
                        'delivery_latitude' => $request->delivery_latitude,
                        'delivery_longitude' => $request->delivery_longitude,
                        'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                        'more_details' => $request->more_details == null ? null : $request->more_details,
                    ]);
                    // update orders status
                    foreach ($cart->orders as $order) {
                        $order->update([
                            'status' => 'new_no_paid',
                            'delivery_date' => $request->delivery_date,
                            'delivery_time' => $request->delivery_time,
                            'delivery_latitude' => $request->delivery_latitude,
                            'delivery_longitude' => $request->delivery_longitude,
                            'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                            'more_details' => $request->more_details == null ? null : $request->more_details,
                        ]);
                        $provider = Provider::find($cart->provider_id);
                        $provider->notify(new NewAdminNotification($cart->id));
                    }
                    $msg = trans('messages.userNewOrder') . $cart->user->name;
                    $data = ['msg' => $msg];
                    Mail::to(Setting::first()->email)->send(new NotifyMail($msg));
                    $success = [
                        'message' => trans('messages.is_joinTrue'),
                    ];
                    return ApiController::respondWithSuccessData($success);
                }
                elseif ($request->payment_type == 'online') {
                    // Online Payment
                    $amount = $cart->total_price;
                    $charge = $request->charge_id;
                    $user = $request->user();
                    $token = Setting::find(1)->myFatoourah_token;
//                    $token = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
                    $data = array(
                        "CustomerName" => $user->name,
                        "PaymentMethodId" => $charge,
                        "NotificationOption" => "ALL",
                        "MobileCountryCode" => "966",
                        "CustomerMobile" => $user->phone_number,
                        "CustomerEmail" => "mail@company.com",
                        "InvoiceValue" => $amount,
                        "DisplayCurrencyIso" => "SAR",
                        "CallBackUrl" => url('/api/v1/check-status'),
                        "ErrorUrl" => url('/api/v1/error-status'),
                        "Language" => "ar",
                        "CustomerReference" => "noshipping-nosupplier",
                        "CustomerAddress" => array(
                            "Block" => "string",
                            "Street" => "string",
                            "HouseBuildingNo" => "string",
                            "Address" => "address",
                            "AddressInstructions" => "string"
                        ),
                        "InvoiceItems" => array(
                            array(
                                "ItemName" => $user->name,
                                "Quantity" => 1,
                                "UnitPrice" => $amount
                            )
                        ),
                    );
                    $fatooraRes = MyFatoorah($token, json_encode($data));
                    $result = json_decode($fatooraRes);
                    if ($result->IsSuccess === true) {
                        $cart->update([
                            'invoice_id' => $result->Data->InvoiceId,
                            'delivery_date' => $request->delivery_date,
                            'delivery_time' => $request->delivery_time,
                            'delivery_latitude' => $request->delivery_latitude,
                            'delivery_longitude' => $request->delivery_longitude,
                            'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                            'more_details' => $request->more_details == null ? null : $request->more_details,
                        ]);
                        $success = [
                            'payment_url' => $result->Data->PaymentURL,
                        ];
                        return ApiController::respondWithSuccessData($success);

                    }
                }
                elseif ($request->payment_type == 'tamara') {
                    // payment by tamara
                    $amount = $cart->total_price;
                    $user = $request->user();
                    $cart->update([
                        'tamara_payment' => 'true',
                        'delivery_date' => $request->delivery_date,
                        'delivery_time' => $request->delivery_time,
                        'delivery_latitude' => $request->delivery_latitude,
                        'delivery_longitude' => $request->delivery_longitude,
                        'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                        'more_details' => $request->more_details == null ? null : $request->more_details,
                        'tamara_instalment' => $request->tamara_instalment,
                    ]);
                    $tamara = tamara_checkOut($cart->id, $user, $amount, $request->tamara_instalment);
                    $success = [
                        'payment_url' => $tamara,
                    ];
                    return ApiController::respondWithSuccessData($success);
                }
            } else {
                $errors = [
                    'message' => trans('messages.emptyCart'),
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        } else {
            $errors = [
                'message' => trans('messages.emptyCart')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function complete_tamara_order(Request $request)
    {
        if ($request->paymentStatus == 'approved') {
            $cart = Cart::where('tamara_order_id', $request->orderId)->first();
            order_authorise($request->orderId);
            tamara_capture($cart->id, $cart->total_price, $cart->user_id, $request->orderId);
            if ($cart) {
                $cart->update([
                    'status' => 'new_paid',
                    'payment_status' => 'done',
                    'payment_type' => 'online',
                    'invoice_id' => null,
                ]);
                $title = trans('messages.orders');
                $message = trans('messages.thanksForShopping');
                $devicesTokens = UserDevice::where('user_id', $cart->user_id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                if ($devicesTokens) {
                    sendNotification($devicesTokens, $title, $message, null);
                }
                saveNotification($cart->user_id, $title, $message, '3', $cart->id, null);

                // update orders status
                if ($cart->orders->count() > 0) {
                    foreach ($cart->orders as $order) {
                        $order->update([
                            'status' => 'new_paid',
                            'delivery_date' => $cart->delivery_date,
                            'delivery_time' => $cart->delivery_time,
                            'delivery_latitude' => $cart->delivery_latitude,
                            'delivery_longitude' => $cart->delivery_longitude,
                            'delivery_address' => $cart->delivery_address == null ? null : $cart->delivery_address,
                            'more_details' => $cart->more_details == null ? null : $cart->more_details,
                        ]);
                        // send notification to provider
                        $provider = Provider::find($cart->provider_id);
                        $provider->notify(new NewAdminNotification($cart->id));
                        if ($order->cart->delivery_price > 0) {
                            $note = $order->delivery_date . ' ' . $order->delivery_time;
                            $obj = array(
                                'sender_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->provider->name,
                                    'email' => $order->provider->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->provider->address,
                                    'landmark' => "",
                                    'city' => array(
                                        'code' => $order->provider->city->code,
                                        'lat' => $order->provider->latitude,
                                        'lon' => $order->provider->longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->provider->phone_number,
                                ),
                                'recipient_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->user->name,
                                    'email' => $order->user->email == null ? "recipient@example.com" : $order->user->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->delivery_address,
                                    'landmark' => "",
                                    'city' => array(
                                        'id' => "26148057",
                                        'lat' => $cart->delivery_latitude,
                                        'lon' => $cart->delivery_longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->user->phone_number,
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
                                "reference_id" => $order->id,
                            );

                            createColdtOrder($obj);
                        }
                    }
                }
                // send notification to admin
                $admin = Admin::where('admin_category_id', 5)->inRandomOrder()->first();
                $admin->notify(new NewAdminNotification($cart->id));

                $msg = 'هناك طلب جديد من العميل : ' . $cart->user->name;
                $data = ['msg' => $msg];
                Mail::to(Setting::first()->email)->send(new NotifyMail($msg));
                // create new Admin Order
                AdminOrder::create([
                    'admin_id' => $admin->id,
                    'order_id' => $cart->id,
                ]);
                return redirect()->route('tamara-success');
            } else {
                $errors = [
                    'message' => 'حدث خطأ ما',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        } else {
            $errors = [
                'message' => 'حدث خطأ ما',
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function fatooraStatus()
    {
        $token = Setting::find(1)->myFatoourah_token;
//        $token = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            // get booking
            $cart = Cart::where('invoice_id', $InvoiceId)->first();
            if ($cart) {
                $cart->update([
                    'status' => 'new_paid',
                    'payment_status' => 'done',
                    'payment_type' => 'online',
                    'invoice_id' => null,
                ]);
                $title = trans('messages.orders');
                $message = trans('messages.thanksForShopping');
                $devicesTokens = UserDevice::where('user_id', $cart->user_id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                if ($devicesTokens) {
                    sendNotification($devicesTokens, $title, $message, null);
                }
                saveNotification($cart->user_id, $title, $message, '3', $cart->id, null);

                // update orders status
                if ($cart->orders->count() > 0) {
                    foreach ($cart->orders as $order) {
                        $order->update([
                            'status' => 'new_paid',
                            'delivery_date' => $cart->delivery_date,
                            'delivery_time' => $cart->delivery_time,
                            'delivery_latitude' => $cart->delivery_latitude,
                            'delivery_longitude' => $cart->delivery_longitude,
                            'delivery_address' => $cart->delivery_address == null ? null : $cart->delivery_address,
                            'more_details' => $cart->more_details == null ? null : $cart->more_details,
                        ]);
                        // send notification to provider
                        $provider = Provider::find($cart->provider_id);
                        $provider->notify(new NewAdminNotification($cart->id));

                        if ($order->cart->delivery_price > 0) {
                            $note = $order->delivery_date . ' ' . $order->delivery_time;
                            $obj = array(
                                'sender_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->provider->name,
                                    'email' => $order->provider->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->provider->address,
                                    'landmark' => "",
                                    'city' => array(
                                        'code' => $order->provider->city->code,
                                        'lat' => $order->provider->latitude,
                                        'lon' => $order->provider->longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->provider->phone_number,
                                ),
                                'recipient_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->user->name,
                                    'email' => $order->user->email == null ? "recipient@example.com" : $order->user->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->delivery_address,
                                    'landmark' => "",
                                    'city' => array(
                                        'id' => "26148057",
                                        'lat' => $cart->delivery_latitude,
                                        'lon' => $cart->delivery_longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->user->phone_number,
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
                                "reference_id" => $order->id,
                            );
                            createColdtOrder($obj);
                        }
                    }
                }
                // send notification to admin
                $admin = Admin::where('admin_category_id', 5)->inRandomOrder()->first();
                $admin->notify(new NewAdminNotification($cart->id));

                $msg = 'هناك طلب جديد من العميل : ' . $cart->user->name;
                $data = ['msg' => $msg];
                Mail::to(Setting::first()->email)->send(new NotifyMail($msg));
                // create new Admin Order
                AdminOrder::create([
                    'admin_id' => $admin->id,
                    'order_id' => $cart->id,
                ]);
                return redirect()->route('fatoora-success');
            } else {
                $errors = [
                    'message' => 'حدث خطأ ما',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        }
    }

    public function errorStatus()
    {
        flash(' حدث خطأ ما حاول في وقت لاحق')->error();
        return redirect()->back();
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $rules = [
//            'provider_id' => 'required|exists:providers,id',
            'status' => 'sometimes|in:new_paid,new_no_paid,works_on,completed,canceled,all',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $provider = Provider::find($request->provider_id);
        if ($request->status == 'all') {
            $carts = Cart::whereNotIn('status', ['opened', 'sent', 'on_cart'])
                ->whereUserId($user->id)
//                ->whereProviderId($provider->id)
                ->orderBy('id', 'desc')
                ->paginate(10);
        } elseif ($request->status != null) {
            $carts = Cart::whereUserId($user->id)
                ->where('status', $request->status)
//                ->whereProviderId($provider->id)
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            $carts = Cart::whereNotIn('status', ['opened', 'sent', 'on_cart'])
                ->whereUserId($user->id)
//                ->whereProviderId($provider->id)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        if ($carts->count() > 0) {
            return ApiController::respondWithSuccessData(new CartCollection($carts));
        } else {
            $errors = [
                'message' => trans('messages.noOrders')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function order_details($id)
    {
        $order = Cart::find($id);
        if ($order) {
            return ApiController::respondWithSuccessData(new CartResource($order));
        } else {
            $errors = [
                'message' => trans('messages.not_found')
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function delete_from_cart(Request $request)
    {
        $rules = [
            'cart_id' => 'required|exists:carts,id',
            'order_id' => 'required|exists:orders,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $cart = Cart::find($request->cart_id);
        if ($cart) {
            if ($cart->status == 'sent') {
                $errors = [
                    'message' => trans('messages.emptyCart'),
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            if ($cart->orders->count() > 0) {
                $order = Order::find($request->order_id);
                // check if there are more than order to same provider
                $check_order = Order::whereCartId($order->cart_id)->first();
                if ($check_order and $check_order->id != $order->id and $cart->orders()->whereProviderId($order->provider_id)->count() > 0) {
                    $cart->update([
                        'items_price' => $cart->items_price - ($order->order_price + $order->options_price),
                        'tax_value' => $cart->tax_value - $order->tax_value,
                        'total_price' => $cart->total_price - ($order->order_price + $order->tax_value + $order->options_price),
                    ]);
                } else {
                    $cart->update([
                        'items_price' => $cart->items_price - ($order->order_price + $order->options_price),
                        'delivery_price' => $cart->delivery_price - $order->delivery_price,
                        'tax_value' => $cart->tax_value - $order->tax_value,
                        'total_price' => $cart->total_price - ($order->order_price + $order->delivery_price + $order->tax_value + $order->options_price),
                    ]);
                }
                $order->delete();
                $orders = Order::whereCartId($cart->id)->count();
                if ($orders == 0) {
                    $cart->delete();
                }
                $success = [
                    'message' => trans('messages.itemDeleted'),
                ];
                return ApiController::respondWithSuccessData($success);
            } else {
                $cart->delete();
                $errors = [
                    'message' => trans('messages.emptyCart'),
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        } else {
            $errors = [
                'message' => trans('messages.emptyCart'),
            ];
            return ApiController::respondWithErrorClient($errors);
        }

    }

}
