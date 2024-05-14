<?php

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\CategoryController;
use \App\Http\Controllers\Api\ProfileController;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\OrderController;
use \App\Http\Controllers\Api\ApiController;
use \App\Http\Controllers\Api\FavoriteController;
use \App\Http\Controllers\Api\ProviderRateController;
use \App\Http\Controllers\Api\ProviderRegisterController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::post('/takia_webhook/{paymentStatus?}/{orderId?}', function (Request $request) {
        if($request->paymentStatus == 'approved')
        {
            order_authorise($request->orderId);
            echo "success payment";
        }else{
            echo "something went wrong";
        }

    });
    Route::get('/takia_webhook/{paymentStatus?}/{orderId?}', function (Request $request) {
        if($request->paymentStatus == 'approved')
        {
            order_authorise($request->orderId);
            echo "success payment";
        }else{
            echo "something went wrong";
        }
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('/check-status/{id?}/{id1?}', 'fatooraStatus');
        Route::get('/error-status', 'errorStatus');

        Route::post('/complete_order' , 'complete_tamara_order');
        Route::get('/complete_order' , 'complete_tamara_order');
    });


    Route::group(['middleware' => ['cors', 'localization']], function () {
        /*user register*/
        Route::controller(AuthController::class)->group(function () {
            Route::post('/register_mobile', 'registerMobile');
            Route::post('/phone_verification', 'register_phone_post');
            Route::post('/resend_code', 'resend_code');
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/forget_password', 'forgetPassword');
            Route::post('/confirm_reset_code', 'confirmResetCode');
            Route::post('/reset_password', 'resetPassword');
            Route::get('/user_data/{id}', 'user_data');
        });
        /*end user register*/

        /**
         *  providers registers routes
        */
        Route::controller(ProviderRegisterController::class)->group(function () {
            Route::post('/provider_register_request', 'provider_register_request');
        });

        /**
         * End providers registers routes
         */
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/terms_and_conditions', 'terms_and_conditions');
            Route::get('/about_us', 'about_us');
            Route::get('/animated_sliders', 'sliders');
            Route::get('/contact_number', 'contact_number');
            Route::get('/advisor_contact_number', 'advisor_contact_number');
            Route::get('/customer_services_number', 'customer_services_number');

            Route::get('/provider/{id}/sliders', 'provider_sliders');
            Route::get('/provider_categories', 'provider_categories');
            Route::get('/checkout/payment-options-pre-check', 'tamara_pre_check');
            Route::get('/banks', 'banks');
            Route::get('/get_user_data/{id}', 'get_user_data');
            Route::post('/store_device_token', 'store_device_token');
            Route::get('/get_visitors_notifications/{device_token}', 'get_visitors_notifications');

        });
        // categories
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories' , 'categories');
            Route::get('/cities' , 'cities');

            Route::get('/sub_categories/{category_id}' , 'sub_categories');
            Route::post('/providers' , 'providers');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::post('/products' , 'products');
            Route::post('/products_search' , 'products_search');
            Route::get('/product_details/{product_id}' , 'product_details');
            Route::post('/home_screen' , 'home_screen');
            Route::post('/recommended_products' , 'recommended_products');
        });


        Route::get('/createColdtOrder' , function (){
            createColdtOrder();
        });
        Route::get('/oauth' , function (){
            oauthToken();
        });
    });

    Route::group(['middleware' => ['auth:api', 'cors', 'localization']], function () {
        /**
         *  Start User Routes
         */
        //====================user app ====================
        Route::controller(AuthController::class)->group(function () {
            Route::post('/change_password', 'changePassword');
            Route::post('/change_phone_number', 'change_phone_number');
            Route::post('/check_code_change_phone_number', 'check_code_changeNumber');
            Route::post('/edit_account', 'user_edit_account');
            //===============logout========================
            Route::post('/logout', 'logout');
        });

        Route::post('/contact_us', 'Api\ProfileController@contact_us');


        /**
         *  Start Order Routes
         */
        Route::controller(OrderController::class)->group(function () {
            Route::post('/add_to_cart' , 'add_to_cart');
            Route::get('/cart_items/{provider_id}' , 'cart_items');
            Route::post('/complete_cart_orders' , 'complete_cart_orders');
            Route::post('/apply_store_receiving' , 'store_receiving');
            Route::post('/orders' , 'orders');
            Route::get('/order_details/{id}' , 'order_details');
            Route::post('/delete_from_cart' , 'delete_from_cart');

        });
        /**
         *  End Order Routes
         */

        /**
         *  End User Routes
         */

        /*notification*/
        Route::controller(ApiController::class)->group(function () {
            Route::get('/list_notifications', 'listNotifications');
            Route::get('/read_notification/{id}', 'read_notification');
            Route::get('/read_all_notifications', 'read_all_notifications');
            Route::get('/un_read_notifications_count', 'un_read_notifications_count');
            Route::post('/delete_Notifications/{id}', 'delete_Notifications');
        });

        /// favorite routes
        Route::controller(FavoriteController::class)->group(function () {
            Route::post('/add_provider_to_favorite' , 'add_provider_to_favorite');
            Route::post('/add_product_to_favorite' , 'add_product_to_favorite');
            Route::get('my_favorite_providers' , 'my_favorite_providers');
            Route::get('my_favorite_products' , 'my_favorite_products');
        });

        // rates routes
        Route::controller(ProviderRateController::class)->group(function () {
            Route::post('/rate_provider' , 'rate_provider');
            Route::get('/provider/{id}/rates' , 'provider_rates');
        });


//        Route::get('/read_all_notification', 'Api\ApiController@read_all_notification');
//        Route::get('/read_notification/{id}', 'Api\ApiController@read_notification');

        /*notification*/

    });
});
