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
            ->first();
        if ($check_provider_cart) {
            // add order to lasted opened cart
            /**
             * check if this product exists at same cart order
             */
            $check_order = Order::whereCartId($check_provider_cart->id)
                ->whereProductId($product->id)
                ->whereUserId($request->user()->id)
                ->whereStatus('on_cart')
                ->first();
            if ($check_order) {
                // add product to same order
                $product_count = $check_order->product_count + $request->product_count;
                $tax = Setting::find(1)->tax;
                $new_order_price = $product->price * $request->product_count;
                $new_order_tax = ($new_order_price * $tax) / 100;
                $order_price = $new_order_price + $check_order->order_price;
                $total_tax = $new_order_tax + $check_order->tax_value;
                $total_price = $new_order_price + $check_order->order_price + $total_tax;
                $check_order->update([
                    'order_price' => $order_price,
                    'product_count' => $product_count,
                    'tax_value' => $total_tax,
                ]);
                // update cart values
                $check_provider_cart->update([
                    'items_price' => $order_price,
                    'total_price' => $total_price,
                    'tax_value' => $total_tax,
                ]);
                $cart = $check_provider_cart;

            } else {
                // create new order
                $order_price = $product->price * $request->product_count;
                $tax = Setting::find(1)->tax;
                $order_tax = ($order_price * $tax) / 100;
                $total_tax = $order_tax;
                $order = Order::create([
                    'cart_id' => $check_provider_cart->id,
                    'provider_id' => $product->provider_id,
                    'user_id' => $request->user()->id,
                    'product_id' => $request->product_id,
                    'order_price' => $order_price,
                    'status' => 'on_cart',
                    'product_count' => $request->product_count,
                    'tax_value' => $total_tax,
                ]);
                $items_price = $check_provider_cart->items_price + $order_price;
                $delivery_price = $check_provider_cart->delivery_price;            // same provider with same delivery value
                $cart_tax_value = $check_provider_cart->tax_value + $total_tax;
                $total_price = $items_price + $delivery_price + $cart_tax_value;
                $check_provider_cart->update([
                    'items_price' => $items_price,
                    'total_price' => $total_price,
                    'tax_value' => $cart_tax_value,
                ]);
                $cart = $check_provider_cart;
            }
        } else {
            // create new cart provider and new product order
            $order_price = $product->price * $request->product_count;
            $tax = Setting::find(1)->tax;
            $order_tax = ($order_price * $tax) / 100;
            $total_tax = $order_tax;
            $total_price = $order_price + $total_tax;

            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'provider_id' => $provider->id,
                'items_price' => $order_price,
                'total_price' => $total_price,
                'transfer_photo' => null,
                'invoice_id' => null,
                'payment_status' => 'wait',
                'status' => 'opened',
                'tax_value' => $total_tax,
            ]);

            $order = Order::create([
                'cart_id' => $cart->id,
                'provider_id' => $product->provider_id,
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'order_price' => $order_price,
                'status' => 'on_cart',
                'product_count' => $request->product_count,
                'tax_value' => $total_tax,
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
                if ($request->store_receiving == 'true' and $provider->store_receiving == 'true' and $cart->store_receiving == 'false')
                {
                    $cart->update([
                       'store_receiving' => 'true',
                    ]);
                    $delivery_price = 0;
                }
                $tax = Setting::find(1)->tax;
                $delivery_tax = ($delivery_price * $tax) / 100;
                $total_tax = $delivery_tax + $cart->tax_value;
                $total_price = $cart->total_price + $delivery_price + $delivery_tax;
                if ($cart->delivery_price == null)
                {
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
                        $provider = Provider::find($order->provider_id);
                        $provider->notify(new NewAdminNotification($order->id));
                    }
                    $msg = trans('messages.userNewOrder') . $cart->user->name;
                    $data = ['msg' => $msg];
                    Mail::to(Setting::first()->email)->send(new NotifyMail($msg));
                    $success = [
                        'message' => trans('messages.is_joinTrue'),
                    ];
                    return ApiController::respondWithSuccessData($success);
                } elseif ($request->payment_type == 'online') {
                    // Online Payment
                    $amount = $cart->total_price;
                    $charge = $request->charge_id;
                    $user = $request->user();
                    $token = Setting::find(1)->myFatoourah_token;
                    $data = array(
                        "CustomerName" => $user->name,
                        "PaymentMethodId" => $charge,
                        "NotificationOption" => "ALL",
                        "MobileCountryCode" => "966",
                        "CustomerMobile" => $user->phone_number,
                        "CustomerEmail" => "mail@company.com",
                        "InvoiceValue" => $amount,
                        "DisplayCurrencyIso" => "kwd",
                        "CallBackUrl" => url('/check-status'),
                        "ErrorUrl" => url('/error-status'),
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
                } elseif ($request->payment_type == 'tamara') {
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
                        $provider = Provider::find($order->provider_id);
                        $provider->notify(new NewAdminNotification($order->id));
                        if ($order->product->delivery == 'yes') {
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
                $title = 'الطلبات';
                $message = 'نشكر لكم تسوقكم وتم أستلام طلبك';
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
                        $provider = Provider::find($order->provider_id);
                        $provider->notify(new NewAdminNotification($order->id));
                        if ($order->product->delivery == 'yes') {
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
                'message' => 'هذا الطلب غير موجود'
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
                    'message' => ' السلة فارغه',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            if ($cart->orders->count() > 0) {
                $order = Order::find($request->order_id);
                // check if there are more than order to same provider
                $check_order = Order::whereProviderId($order->provider_id)
                    ->whereCartId($order->cart_id)
                    ->first();
                if ($check_order and $check_order->id != $order->id and $cart->orders()->whereProviderId($order->provider_id)->count() > 0) {
                    $cart->update([
                        'items_price' => $cart->items_price - $order->order_price,
                        'tax_value' => $cart->tax_value - $order->tax_value,
                        'total_price' => $cart->total_price - ($order->order_price + $order->tax_value),
                    ]);
                } else {
                    $cart->update([
                        'items_price' => $cart->items_price - $order->order_price,
                        'delivery_price' => $cart->delivery_price - $order->delivery_price,
                        'tax_value' => $cart->tax_value - $order->tax_value,
                        'total_price' => $cart->total_price - ($order->order_price + $order->delivery_price + $order->tax_value),
                    ]);
                }
                $order->delete();
                $orders = Order::whereCartId($cart->id)->count();
                if ($orders == 0) {
                    $cart->delete();
                }
                $success = [
                    'message' => 'تم حذف العنصر من السلة بنجاح',
                ];
                return ApiController::respondWithSuccessData($success);
            } else {
                $cart->delete();
                $errors = [
                    'message' => ' السلة فارغه',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        } else {
            $errors = [
                'message' => ' السلة فارغه',
            ];
            return ApiController::respondWithErrorClient($errors);
        }

    }

}
