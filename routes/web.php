<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\AdminController\Admin\LoginController;
use \App\Http\Controllers\AdminController\HomeController;
use \App\Http\Controllers\AdminController\AdminController;
use \App\Http\Controllers\AdminController\UserController;
use \App\Http\Controllers\AdminController\CityController;
use \App\Http\Controllers\AdminController\CategoryController;
use \App\Http\Controllers\AdminController\ProductCategoryController;
use \App\Http\Controllers\AdminController\SubCategoryController;
use \App\Http\Controllers\AdminController\AdminCategoryController;
use \App\Http\Controllers\AdminController\ProviderRegisterController;
use \App\Http\Controllers\AdminController\ProductController;
use \App\Http\Controllers\AdminController\ProviderController;
use \App\Http\Controllers\AdminController\ContactsController;
use \App\Http\Controllers\AdminController\ProviderSliderController;
use \App\Http\Controllers\AdminController\BankController;
use \App\Http\Controllers\AdminController\SliderController;
use \App\Http\Controllers\AdminController\AnimatedSliderController;
use \App\Http\Controllers\AdminController\SettingController;
use \App\Http\Controllers\AdminController\PageController;
use \App\Http\Controllers\AdminController\OrderController;
use \App\Http\Controllers\AdminController\ProviderRateController;
use \App\Http\Controllers\AdminController\ProductSizeController;
use \App\Http\Controllers\AdminController\ProductOptionController;
use \App\Http\Controllers\AdminController\ProductModifierController;

// provider controller
use \App\Http\Controllers\ProviderController\HomeController as ProviderHomeController;
use \App\Http\Controllers\ProviderController\Provider\LoginController as ProviderLoginController;
use \App\Http\Controllers\ProviderController\Provider\ForgotPasswordController as ProviderForgotPasswordController;
use \App\Http\Controllers\ProviderController\ProviderController as ProviderProviderController;
use \App\Http\Controllers\ProviderController\ProductController as ProviderProductController;
use \App\Http\Controllers\ProviderController\OrderController as ProviderOrderController;
use \App\Http\Controllers\ProviderController\CommissionController as ProviderCommissionController;
use \App\Http\Controllers\ProviderController\ProviderProductSizeController;
use \App\Http\Controllers\ProviderController\ProviderProductOptionController;
use \App\Http\Controllers\ProviderController\ProviderProductModifierController;


use \App\Http\Controllers\Api\OrderController as ApiOrderController;


Route::get('/tamara' , function (){
    tamara();
});

Route::get('/', ['middleware'=> 'auth:admin', 'uses'=>'AdminController\HomeController@index']);

Route::get('/update_providers' , function (){
    $users  = \App\Models\ProviderMainCategory::all();
    foreach ($users as  $user)
    {
        $user->provider->update([
            'category_id' => $user->category_id,
            'provider_category_arrange' => $user->arrange,
        ]);
    }
    echo 'success';
});

Route::get('/update_users' , function (){
    $users  = \App\Models\User::all();
    foreach ($users as  $user)
    {
        $user->update([
            'api_token' => null
        ]);
    }
    echo 'success';
});

Route::get('/fatoora/success', function () {
    return view('fatoora');
})->name('fatoora-success');

Route::get('/tamara/success', function () {
    return view('tamara');
})->name('tamara-success');

Route::get('/product_photos' , function (){
    $products = \App\Product::all();
    foreach ($products as $product)
    {
        $product->update(['delivery_price' => \App\Setting::first()->delivery_price]);
    }
});

Auth::routes();

Route::get('/markAdminNotifyAsRead', function (){
    auth()->guard('admin')->user()->unreadNotifications->markAsRead();
    return redirect()->back();
});
Route::get('/markProviderNotifyAsRead', function (){
    auth()->guard('provider')->user()->unreadNotifications->markAsRead();
    return redirect()->back();
});

Route::get('/home', 'HomeController@index')->name('home');

/*admin panel routes*/

Route::get('/admin/home', [HomeController::class, 'index'])->name('admin.home');
Route::prefix('admin')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');



    Route::group(['middleware'=> ['web','auth:admin']],function(){
        // public notifications
        Route::controller(HomeController::class)->group(function () {
            Route::get('public_notifications' , 'public_notifications')->name('public_notifications');
            Route::post('store_public_notifications' , 'store_public_notifications')->name('storePublicNotification');
            Route::get('user_notifications' , 'user_notifications')->name('user_notifications');
            Route::post('storeUserNotification' , 'store_user_notifications')->name('storeUserNotification');
            Route::get('specified_notification' , 'specified_notification')->name('specified_notification');
            Route::post('specified_notification' , 'store_specified_notification')->name('storeSpecified_notification');
        });


        Route::controller(SettingController::class)->group(function () {
            Route::get('setting','index')->name('settings');
            Route::post('add/settings','store');
            Route::post('drivers_commission','drivers_commission')->name('drivers_commission');
        });
        Route::controller(ProviderRegisterController::class)->group(function () {
            Route::get('provider_registers/{status?}','index');
            Route::get('complete_provider/{id}/{status?}','complete_provider')->name('complete_provider');
            Route::post('cancel_provider/{id}/{status?}','cancel_provider')->name('cancel_provider');
        });

        Route::controller(PageController::class)->group(function () {
            Route::get('pages/about','about');
            Route::post('add/pages/about','store_about');

            Route::get('pages/terms','terms');
            Route::post('add/pages/terms','store_terms');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('users/{type}','index');
            Route::get('add/user/{type}','create');
            Route::post('add/user/{type}','store');
            Route::get('edit/user/{id}','edit');
            Route::post('update/user/{id}','update');
            Route::post('update/pass/{id}','update_pass');
            Route::post('update/privacy/{id}','update_privacy');
            Route::get('delete/{id}/user','destroy');
            Route::get('active_user/{id}/{status}','active_user')->name('active_user');
        });

        // Cities  Routes
        Route::controller(CityController::class)->group(function () {
            Route::get('cities','index')->name('City');
            Route::get('cities/create','create')->name('createCity');
            Route::post('cities/store','store')->name('storeCity');
            Route::get('cities/edit/{id}','edit')->name('editCity');
            Route::post('cities/update/{id}','update')->name('updateCity');
            Route::get('cities/delete/{id}','destroy')->name('deleteCity');
        });

        // Banks  Routes
        Route::controller(BankController::class)->group(function () {
            Route::get('banks','index')->name('Bank');
            Route::get('banks/create','create')->name('createBank');
            Route::post('banks/store','store')->name('storeBank');
            Route::get('banks/edit/{id}','edit')->name('editBank');
            Route::post('banks/update/{id}','update')->name('updateBank');
            Route::get('banks/delete/{id}','destroy')->name('deleteBank');
        });

        // Sliders  Routes
        Route::controller(SliderController::class)->group(function () {
            Route::get('sliders','index')->name('Slider');
            Route::get('sliders/create','create')->name('createSlider');
            Route::post('sliders/store','store')->name('storeSlider');
            Route::get('sliders/edit/{id}','edit')->name('editSlider');
            Route::post('sliders/update/{id}','update')->name('updateSlider');
            Route::get('sliders/delete/{id}','destroy')->name('deleteSlider');
        });

        // Animated Sliders  Routes
        Route::controller(AnimatedSliderController::class)->group(function () {
            Route::get('animated_sliders','index')->name('AnimatedSlider');
            Route::get('animated_sliders/create','create')->name('createAnimatedSlider');
            Route::post('animated_sliders/store','store')->name('storeAnimatedSlider');
            Route::get('animated_sliders/edit/{id}','edit')->name('editAnimatedSlider');
            Route::post('animated_sliders/update/{id}','update')->name('updateAnimatedSlider');
            Route::get('animated_sliders/delete/{id}','destroy')->name('deleteAnimatedSlider');

        });
        // provider Sliders  Routes
        Route::controller(ProviderSliderController::class)->group(function () {
            Route::get('provider_sliders/{id}','index')->name('providerSlider');
            Route::get('provider_sliders/{id}/create','create')->name('createproviderSlider');
            Route::post('provider_sliders/{id}/store','store')->name('storeproviderSlider');
            Route::get('provider_sliders/edit/{id}','edit')->name('editproviderSlider');
            Route::post('provider_sliders/update/{id}','update')->name('updateproviderSlider');
            Route::get('provider_sliders/delete/{id}','destroy')->name('deleteproviderSlider');
        });

        //================================  Contacts   ======================================================= //
        Route::controller(ContactsController::class)->group(function () {
            Route::get('contacts','index')->name('Contact');
            Route::get('ArchivedContact','ArchivedContact')->name('ArchivedContacts');
            Route::get('archived_contacts/{id}/{status}','archived')->name('archivedContact');
            Route::get('contacts/show/{id}','show')->name('showContact');
            Route::get('contacts/delete/{id}','destroy')->name('deleteContact');
            Route::post('contacts/reply','reply')->name('replyContact');
        });
        //================================  Contacts   ======================================================= //


        // Category  Routes
        Route::controller(CategoryController::class)->group(function () {
            Route::get('categories','index')->name('Category');
            Route::get('categories/create','create')->name('createCategory');
            Route::post('categories/store','store')->name('storeCategory');
            Route::get('categories/edit/{id}','edit')->name('editCategory');
            Route::post('categories/update/{id}','update')->name('updateCategory');
            Route::get('categories/delete/{id}','destroy')->name('deleteCategory');
            Route::get('categories/arrange/{id}','arrange')->name('arrangeCategory');
            Route::post('categories/arrange/{id}','arrange_submit')->name('submitArrangeCategory');
        });

        // product Category  Routes
        Route::controller(ProductCategoryController::class)->group(function () {
            Route::get('product_categories','index')->name('ProductCategory');
            Route::get('product_categories/create','create')->name('createProductCategory');
            Route::post('product_categories/store','store')->name('storeProductCategory');
            Route::get('product_categories/edit/{id}','edit')->name('editProductCategory');
            Route::post('product_categories/update/{id}','update')->name('updateProductCategory');
            Route::get('product_categories/delete/{id}','destroy')->name('deleteProductCategory');
        });

        //  Sub Category  Routes
        Route::controller(SubCategoryController::class)->group(function () {
            Route::get('sub_categories','index')->name('SubCategory');
            Route::get('sub_categories/create','create')->name('createSubCategory');
            Route::post('sub_categories/store','store')->name('storeSubCategory');
            Route::get('sub_categories/edit/{id}','edit')->name('editSubCategory');
            Route::post('sub_categories/update/{id}','update')->name('updateSubCategory');
            Route::get('sub_categories/delete/{id}','destroy')->name('deleteSubCategory');

        });




        // providers  Routes
        Route::controller(ProviderController::class)->group(function () {
            Route::get('providers','index')->name('Provider');
            Route::get('providers/create','create')->name('createProvider');
            Route::post('providers/store','store')->name('storeProvider');
            Route::get('providers/edit/{id}','edit')->name('editProvider');
            Route::post('providers/update/{id}','update')->name('updateProvider');
            Route::get('providers/delete/{id}','destroy')->name('deleteProvider');
            Route::get('providers/arrange/{id}','arrange')->name('ArrangeProvider');
            Route::post('providers/arrange/{id}','arrange_submit')->name('submitArrangeProvider');
            Route::get('provider_categories/{id}','provider_categories')->name('provider_categories');

            Route::get('providers/special/{id}/{status}','special')->name('specialProvider');
            Route::get('providers/ProviderMainCatRemove/{id}','ProviderMainCatRemove')->name('ProviderMainCatRemove');
            Route::get('providers/ProviderSubCatRemove/{id}','ProviderSubCatRemove')->name('ProviderSubCatRemove');
            Route::get('providers/vip/{id}/{status}','vip')->name('vipProvider');
            Route::get('providers/stop/{id}/{state}','stop')->name('stopProvider');
        });
        Route::controller(ProviderRateController::class)->group(function () {
            Route::get('providers/{id}/rates','index')->name('showProviderRates');
            Route::get('providers/{id}/rates/create','create')->name('createProviderRates');
            Route::post('providers/{id}/rates/store','store')->name('storeProviderRates');
            Route::get('providers/{id}/rates/edit','edit')->name('editProviderRates');
            Route::post('providers/{id}/rates/update','update')->name('updateProviderRates');
            Route::get('providers/rates/delete/{id}','destroy')->name('deleteProviderRate');
        });

        Route::get('get/sub_categories/{id}', [ProviderController::class, 'sub_categories'])->name('sub_categories');


        // products  Routes
        Route::controller(ProductController::class)->group(function () {
            Route::get('products','index')->name('Product');
            Route::get('recomended_products','recomended_products')->name('recomended_products');
            Route::get('waiting_accept_products','waiting_accept_products')->name('waiting_accept_products');
            Route::get('products/create','create')->name('createProduct');
            Route::post('products/store','store')->name('storeProduct');
            Route::get('products/edit/{id}','edit')->name('editProduct');
            Route::post('products/update/{id}','update')->name('updateProduct');
            Route::get('products/delete/{id}','destroy')->name('deleteProduct');
            Route::get('products/stop/{id}/{state}','stop')->name('stopProduct');
            Route::get('products/recommend/{id}/{status}','recommend')->name('recommendProduct');
            Route::get('products/images/remove/{id}','imageProductRemove')->name('imageProductRemove');
            Route::get('get_provider/{id}','get_provider')->name('get_provider');
            Route::get('AcceptProduct/{id}','AcceptProduct')->name('AcceptProduct');
        });
        // product sizes routes
        Route::controller(ProductSizeController::class)->group(function () {
            Route::get('product_sizes/{id}','index')->name('ProductSize');
            Route::get('product_sizes/{id}/create','create')->name('createProductSize');
            Route::post('product_sizes/{id}/store','store')->name('storeProductSize');
            Route::get('product_sizes/edit/{id}','edit')->name('editProductSize');
            Route::post('product_sizes/update/{id}','update')->name('updateProductSize');
            Route::get('product_sizes/delete/{id}','destroy')->name('deleteProductSize');
        });
        // product options routes
        Route::controller(ProductOptionController::class)->group(function () {
            Route::get('product_options/{id}','index')->name('ProductOption');
            Route::get('product_options/{id}/create','create')->name('createProductOption');
            Route::post('product_options/{id}/store','store')->name('storeProductOption');
            Route::get('product_options/edit/{id}','edit')->name('editProductOption');
            Route::post('product_options/update/{id}','update')->name('updateProductOption');
            Route::get('product_options/delete/{id}','destroy')->name('deleteProductOption');
        });
        Route::controller(ProductModifierController::class)->group(function () {
            Route::get('product_modifiers/{id}','index')->name('ProductModifier');
            Route::get('product_modifiers/{id}/create','create')->name('createProductModifier');
            Route::post('product_modifiers/{id}/store','store')->name('storeProductModifier');
            Route::get('product_modifiers/edit/{id}','edit')->name('editProductModifier');
            Route::post('product_modifiers/update/{id}','update')->name('updateProductModifier');
            Route::get('product_modifiers/delete/{id}','destroy')->name('deleteProductModifier');
        });




        // products  Routes
        Route::controller(AdminCategoryController::class)->group(function () {
            Route::get('admin_categories','index')->name('AdminCategory');
            Route::get('admin_categories/create','create')->name('createAdminCategory');
            Route::post('admin_categories/store','store')->name('storeAdminCategory');
            Route::get('admin_categories/edit/{id}','edit')->name('editAdminCategory');
            Route::post('admin_categories/update/{id}','update')->name('updateAdminCategory');
            Route::get('admin_categories/delete/{id}','destroy')->name('deleteAdminCategory');
            Route::get('admin_categories/images/remove/{id}','imageProductRemove')->name('imageProductRemove');

        });



        // Admins Route
        Route::resource('/admins',AdminController::class);

        Route::get('/profile','AdminController\AdminController@my_profile');
        Route::post('/profileEdit', 'AdminController\AdminController@my_profile_edit');
        Route::get('/profileChangePass','AdminController\AdminController@change_pass');
        Route::post('/profileChangePass','AdminController\AdminController@change_pass_update');
        Route::get('/admin_delete/{id}','AdminController\AdminController@admin_delete');

        /**
         *  Start Orders Routes
         */
        Route::controller(OrderController::class)->group(function () {
            Route::get('/new_not_paid_orders','new_not_paid_orders');
            Route::get('/orders/payments/{id}','PaymentDone')->name('PaymentDone');
            Route::get('/orders/status/cancel/{id}','CancelOrder')->name('CancelOrder');
            Route::get('/orders/show/{id}/{notify_id?}','showOrder')->name('showOrder');
            // admin orders
            Route::get('/orders/{status?}','orders')->name('Order');
            Route::get('/orders_completed/{provider_id}','orders_completed')->name('orders_completed');

            Route::post('/orders/change_status/{order_id}','change_status')->name('ChangeOrderStatus');
            Route::post('/orders/redirection/{order_id}','redirection')->name('Redirection');
            Route::get('/orderHistory/{order_id}','orderHistory')->name('orderHistory');
            Route::get('/AdminAddNote/{order_id}','AdminAddNote')->name('AdminAddNote');
            Route::post('/storeAdminNote/{order_id}','storeAdminNote')->name('storeAdminNote');

        });

        // provider Commissions Histories
        Route::get('/providers/commissions/{provider_id}','AdminController\CommissionController@provider_commissions')->name('providerCommissions');
        Route::get('/providers/commissions/confirm/{commission_id}','AdminController\CommissionController@confirm_commission')->name('ConfirmCommission');
        Route::get('/providers/commissions/cancel/{commission_id}','AdminController\CommissionController@cancel_commission')->name('CancelCommission');
        /**
         *  End Orders Routes
         */

    });



});
Route::get('/employees/home', [HomeController::class, 'index'])->name('employees.home');

Route::prefix('employees')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');


    Route::group(['middleware'=> ['web','auth:admin']],function(){

        //================================  Contacts   ======================================================= //
        Route::controller(ContactsController::class)->group(function () {
            Route::get('contacts','index')->name('ContactE');
            Route::get('ArchivedContact','ArchivedContact')->name('ArchivedContactsE');
            Route::get('archived_contacts/{id}/{status}','archived')->name('archivedContactE');
            Route::get('contacts/show/{id}','show')->name('showContact');
            Route::get('contacts/delete/{id}','destroy')->name('deleteContact');
            Route::post('contacts/reply','reply')->name('replyContact');
        });
        //================================  Contacts   ======================================================= //



        // providers  Routes
        Route::controller(ProviderController::class)->group(function () {
            Route::get('providers','index')->name('ProviderE');
            Route::get('get/sub_categories/{id}','sub_categories')->name('sub_categories');
        });


        Route::controller(ProductController::class)->group(function () {
            Route::get('products','index')->name('Product');
            Route::get('recomended_products','recomended_products')->name('recomended_products');
            Route::get('products/create','create')->name('createProduct');
            Route::post('products/store','store')->name('storeProduct');
            Route::get('products/edit/{id}','edit')->name('editProduct');
            Route::post('products/update/{id}','update')->name('updateProduct');
            Route::get('products/delete/{id}','destroy')->name('deleteProduct');
            Route::get('products/stop/{id}/{state}','stop')->name('stopProduct');
            Route::get('products/recommend/{id}/{status}','recommend')->name('recommendProduct');
            Route::get('products/images/remove/{id}','imageProductRemove')->name('imageProductRemove');
        });

        // products  Routes
        Route::controller(AdminCategoryController::class)->group(function () {
            Route::get('admin_categories','index')->name('AdminCategory');
            Route::get('admin_categories/create','create')->name('createAdminCategory');
            Route::post('admin_categories/store','store')->name('storeAdminCategory');
            Route::get('admin_categories/edit/{id}','edit')->name('editAdminCategory');
            Route::post('admin_categories/update/{id}','update')->name('updateAdminCategory');
            Route::get('admin_categories/delete/{id}','destroy')->name('deleteAdminCategory');
            Route::get('admin_categories/images/remove/{id}','imageProductRemove')->name('imageProductRemove');

        });


        /**
         *  Start Orders Routes
         */
        Route::controller(OrderController::class)->group(function () {
            Route::get('/new_not_paid_orders','new_not_paid_orders');
            Route::get('/orders/payments/{id}','PaymentDone')->name('PaymentDone');
            Route::get('/orders/status/cancel/{id}','CancelOrder')->name('CancelOrder');
            Route::get('/orders/show/{id}/{notify_id?}','showOrder')->name('showOrderE');
            // admin orders
            Route::get('/orders/{status?}','orders')->name('Order');
            Route::post('/orders/change_status/{order_id}','change_status')->name('ChangeOrderStatus');
            Route::post('/orders/redirection/{order_id}','redirection')->name('Redirection');
            Route::get('/orderHistory/{order_id}','orderHistory')->name('orderHistory');
            Route::get('/AdminAddNote/{order_id}','AdminAddNote')->name('AdminAddNote');
            Route::post('/storeAdminNote/{order_id}','storeAdminNote')->name('storeAdminNote');

        });
        /**
         *  End Orders Routes
         */

    });


});

/**
 * Start provider Routes
 */
Route::get('products/images/remove/{id}', [ProductController::class, 'imageProductRemove'])->name('imageProductRemove');

Route::get('/provider/home', [ProviderHomeController::class, 'index'])->name('provider.home');
Route::prefix('provider')->group(function () {
    Route::controller(ProviderLoginController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('provider.login');
        Route::post('login', 'login')->name('provider.login.submit');
        Route::post('logout', 'logout')->name('provider.logout');
    });
    Route::controller(ProviderForgotPasswordController::class)->group(function () {
        Route::get('password/reset', '@showLinkRequestForm')->name('provider.password.request');
        Route::post('password/email', '@sendResetLinkEmail')->name('provider.password.email');
        Route::get('password/reset/{token}', 'showResetForm')->name('provider.password.reset');
        Route::post('password/reset', 'reset')->name('provider.password.update');

    });

    Route::group(['middleware'=> ['web','auth:provider']],function(){
        Route::controller(ProviderProviderController::class)->group(function () {
            Route::get('/profile','my_profile');
            Route::post('/profileEdit', 'my_profile_edit');
            Route::get('/profileChangePass','change_pass');
            Route::post('/profileChangePass','change_pass_update');
        });
        Route::get('/admin_delete/{id}','AdminController\ProviderController@admin_delete');

        // products  Routes
        Route::controller(ProviderProductController::class)->group(function () {
            Route::get('products','index')->name('MyProduct');
            Route::get('products/create','create')->name('createMyProduct');
            Route::post('products/store','store')->name('storeMyProduct');
            Route::get('products/edit/{id}','edit')->name('editMyProduct');
            Route::post('products/update/{id}','update')->name('updateMyProduct');
            Route::get('products/delete/{id}','destroy')->name('deleteMyProduct');
        });
        // product sizes routes
        Route::controller(ProviderProductSizeController::class)->group(function () {
            Route::get('provider_product_sizes/{id}','index')->name('ProviderProductSize');
            Route::get('provider_product_sizes/{id}/create','create')->name('createProviderProductSize');
            Route::post('provider_product_sizes/{id}/store','store')->name('storeProviderProductSize');
            Route::get('provider_product_sizes/edit/{id}','edit')->name('editProviderProductSize');
            Route::post('provider_product_sizes/update/{id}','update')->name('updateProviderProductSize');
            Route::get('provider_product_sizes/delete/{id}','destroy')->name('deleteProviderProductSize');

        });
        // product options routes
        Route::controller(ProviderProductOptionController::class)->group(function () {
            Route::get('provider_product_options/{id}','index')->name('ProviderProductOption');
            Route::get('provider_product_options/{id}/create','create')->name('createProviderProductOption');
            Route::post('provider_product_options/{id}/store','store')->name('storeProviderProductOption');
            Route::get('provider_product_options/edit/{id}','edit')->name('editProviderProductOption');
            Route::post('provider_product_options/update/{id}','update')->name('updateProviderProductOption');
            Route::get('provider_product_options/delete/{id}','destroy')->name('deleteProviderProductOption');
        });
        Route::controller(ProviderProductModifierController::class)->group(function () {
            Route::get('provider_product_modifiers/{id}','index')->name('ProviderProductModifier');
            Route::get('provider_product_modifiers/{id}/create','create')->name('createProviderProductModifier');
            Route::post('provider_product_modifiers/{id}/store','store')->name('storeProviderProductModifier');
            Route::get('provider_product_modifiers/edit/{id}','edit')->name('editProviderProductModifier');
            Route::post('provider_product_modifiers/update/{id}','update')->name('updateProviderProductModifier');
            Route::get('provider_product_modifiers/delete/{id}','destroy')->name('deleteProviderProductModifier');
        });

        // order routes
        Route::controller(ProviderOrderController::class)->group(function () {
            Route::get('orders/{status?}','index')->name('MyOrder');
            Route::get('orders/show/{order_id}/{notify_id?}','show')->name('showProviderOrder');
        });

        // commission routes
        Route::controller(ProviderCommissionController::class)->group(function () {
            Route::get('commissions','index')->name('MyCommission');
            Route::get('commissions/create','create')->name('createMyCommission');
            Route::post('commissions/store','store')->name('storeMyCommission');
            Route::get('commissions/edit/{id}','edit')->name('editMyCommission');
            Route::post('commissions/update/{id}','update')->name('updateMyCommission');
            Route::get('commissions/delete/{id}','destroy')->name('deleteMyCommission');
        });
    });
});
/**
 * End provider Routes
 */
Route::get('/Privacy-Policy' , function ()
{
    return view('admin.privacyAndPolicy');
});
