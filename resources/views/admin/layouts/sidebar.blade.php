<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item start active open">
                    <a href="{{url('/admin/home')}}" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">الرئيسية</span>
                        <span class="selected"></span>

                    </a>
                </li>
            @elseif(auth()->guard('admin')->user()->admin_category_id != 4)
                <li class="nav-item start active open">
                    <a href="{{url('/employees/home')}}" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">الرئيسية</span>
                        <span class="selected"></span>

                    </a>
                </li>
            @endif
            <li class="heading">
                <h3 class="uppercase">القائمة الجانبية</h3>
            </li>

            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), 'admins') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-users" style="color: aqua;"></i>
                        <span class="title">المشرفين</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('/admin/admins') }}" class="nav-link ">
                                <span class="title">عرض المشرفين</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/admins/create') }}" class="nav-link ">
                                <span class="title">اضافة مشرف</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/admin_categories') }}" class="nav-link ">
                                <span class="title"> أقسام الموظفين </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/users/customer') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-users" style="color: aqua;"></i>
                        <span class="title">المستخدمين</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('/admin/users/customer') }}" class="nav-link ">
                                <span class="title"> المستخدمين </span>
                                <?php $customer = \App\Models\User::count(); ?>
                                <span class="badge badge-success">{{$customer}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/categories') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/categories')}}" class="nav-link ">
                        <i class="fa fa-list-alt" style="color: aqua;"></i>
                        <span class="title"> الأقسام الرئيسية </span>
                        <span class="pull-right-container"></span>
                        <?php $categories = \App\Models\Category::count(); ?>
                        <span class="badge badge-success">{{$categories}}</span>
                    </a>
                </li>
            <!--<li class="nav-item {{ strpos(URL::current(), 'admin/sub_categories') !== false ? 'active' : '' }}">-->
            <!--    <a href="{{url('/admin/sub_categories')}}" class="nav-link ">-->
                <!--        <i class="fa fa-list-alt" style="color: aqua;"></i>-->
                <!--        <span class="title"> الأقسام الفرعية </span>-->
                <!--        <span class="pull-right-container"></span>-->
            <!--        <?php $sub_categories = \App\Models\SubCategory::count(); ?>-->
            <!--        <span class="badge badge-success">{{$sub_categories}}</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li class="nav-item {{ strpos(URL::current(), 'admin/product_categories') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/product_categories')}}" class="nav-link ">
                        <i class="fa fa-list-alt" style="color: aqua;"></i>
                        <span class="title"> الأقسام للمزودين </span>
                        <span class="pull-right-container"></span>
                        <?php $product_categories = \App\Models\ProductCategory::count(); ?>
                        <span class="badge badge-success">{{$product_categories}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/cities') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/cities')}}" class="nav-link ">
                        <i class="fa fa-flag-checkered" style="color: aqua;"></i>
                        <span class="title"> المدن </span>
                        <span class="pull-right-container"></span>
                        <?php $cities = \App\Models\City::count(); ?>
                        <span class="badge badge-success">{{$cities}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/public_notifications') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-bell" style="color: aqua;"></i>
                        <span class="title">الأشعارات</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item {{ strpos(URL::current(), 'admin/public_notifications') !== false ? 'active' : '' }}">
                            <a href="{{url('/admin/public_notifications')}}" class="nav-link ">
                                <i class="fa fa-bell-o" style="color: aqua;"></i>
                                <span class="title">ألاشعارات العامه</span>
                                <span class="pull-right-container"></span>

                            </a>
                        </li>
                    <!--<li class="nav-item {{ strpos(URL::current(), 'admin/specified_notification') !== false ? 'active' : '' }}">-->
                    <!--    <a href="{{url('/admin/specified_notification')}}" class="nav-link ">-->
                        <!--        <i class="fa fa-bell-o" style="color: aqua;"></i>-->
                        <!--        <span class="title">إشعارات لفئة معينة</span>-->
                        <!--        <span class="pull-right-container"></span>-->

                        <!--    </a>-->
                        <!--</li>-->
                        <li class="nav-item {{ strpos(URL::current(), 'admin/user_notifications') !== false ? 'active' : '' }}">
                            <a href="{{url('/admin/user_notifications')}}" class="nav-link ">
                                <i class="fa fa-bell-o" style="color: aqua;"></i>
                                <span class="title"> إشعارات لأشخاص محددين </span>
                                <span class="pull-right-container"></span>

                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/provider_registers/new') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-users" style="color: aqua;"></i>
                        <span class="title">طلبات تسجيل مزود خدمة</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item {{ strpos(URL::current(), 'admin/provider_registers/new') !== false ? 'active' : '' }}">
                            <a href="{{url('/admin/provider_registers/new')}}" class="nav-link ">
                                <i class="fa fa-users" style="color: aqua;"></i>
                                <span class="title">جديد</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), 'admin/provider_registers/completed') !== false ? 'active' : '' }}">
                            <a href="{{url('/admin/provider_registers/completed')}}" class="nav-link ">
                                <i class="fa fa-users" style="color: aqua;"></i>
                                <span class="title">مكتمل</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                        <li class="nav-item {{ strpos(URL::current(), 'admin/provider_registers/canceled') !== false ? 'active' : '' }}">
                            <a href="{{url('/admin/provider_registers/canceled')}}" class="nav-link ">
                                <i class="fa fa-users" style="color: aqua;"></i>
                                <span class="title">ملغي</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ strpos(URL::current(), '/admin/products') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/products')}}" class="nav-link ">
                        <i class="fa fa-product-hunt" style="color: aqua;"></i>
                        <span class="title">  المنتجات </span>
                        <span class="pull-right-container"></span>
                        <?php $products = \App\Models\Product::where('recomended', 'false')->whereAccepted('true')->count(); ?>
                        <span class="badge badge-success">{{$products}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), '/admin/recomended_products') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/recomended_products')}}" class="nav-link ">
                        <i class="fa fa-product-hunt" style="color: aqua;"></i>
                        <span class="title">  الموصي بها </span>
                        <span class="pull-right-container"></span>
                        <?php $products = \App\Models\Product::where('recomended', 'true')->count(); ?>
                        <span class="badge badge-success">{{$products}}</span>
                    </a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), 'admin/providers') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/providers')}}" class="nav-link ">
                        <i class="fa fa-users" style="color: aqua;"></i>
                        <span class="title"> المزودين </span>
                        <span class="pull-right-container"></span>
                        <?php $providers = \App\Models\Provider::count(); ?>
                        <span class="badge badge-success">{{$providers}}</span>
                    </a>
                </li>
            @elseif(auth()->guard('admin')->user()->admin_category_id == 3 or auth()->guard('admin')->user()->admin_category_id == 6)
                <li class="nav-item {{ strpos(URL::current(), 'employees/providers') !== false ? 'active' : '' }}">
                    <a href="{{url('/employees/providers')}}" class="nav-link ">
                        <i class="fa fa-users" style="color: aqua;"></i>
                        <span class="title"> المزودين </span>
                        <span class="pull-right-container"></span>
                        <?php $providers = \App\Models\Provider::count(); ?>
                        <span class="badge badge-success">{{$providers}}</span>
                    </a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->admin_category_id == 6)
                <li class="nav-item {{ strpos(URL::current(), '/employees/products') !== false ? 'active' : '' }}">
                    <a href="{{url('/employees/products')}}" class="nav-link ">
                        <i class="fa fa-product-hunt" style="color: aqua;"></i>
                        <span class="title">  المنتجات </span>
                        <span class="pull-right-container"></span>
                        <?php $products = \App\Models\Product::where('recomended', 'false')->count(); ?>
                        <span class="badge badge-success">{{$products}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), '/employees/recomended_products') !== false ? 'active' : '' }}">
                    <a href="{{url('/employees/recomended_products')}}" class="nav-link ">
                        <i class="fa fa-product-hunt" style="color: aqua;"></i>
                        <span class="title">  الموصي بها </span>
                        <span class="pull-right-container"></span>
                        <?php $products = \App\Models\Product::where('recomended', 'true')->count(); ?>
                        <span class="badge badge-success">{{$products}}</span>
                    </a>
                </li>

            @endif

            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), '/admin/new_not_paid_orders') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/new_not_paid_orders')}}" class="nav-link ">
                        <i class="fa fa-first-order" style="color: aqua;"></i>
                        <span class="title"> طلبات بأنتظار تأكيد الدفع </span>
                        <span class="pull-right-container"></span>
                        <?php $new_not_paid_orders = \App\Models\Cart::where('payment_type', 'bank_transfer')
                            ->where('status', 'new_no_paid')->count(); ?>
                        <span class="badge badge-success">{{$new_not_paid_orders}}</span>
                    </a>
                </li>
            @elseif(auth()->guard('admin')->user()->admin_category_id == 3)
                <li class="nav-item {{ strpos(URL::current(), '/employees/new_not_paid_orders') !== false ? 'active' : '' }}">
                    <a href="{{url('/employees/new_not_paid_orders')}}" class="nav-link ">
                        <i class="fa fa-first-order" style="color: aqua;"></i>
                        <span class="title"> طلبات بأنتظار تأكيد الدفع </span>
                        <span class="pull-right-container"></span>
                        <?php $new_not_paid_orders = \App\Models\Cart::where('payment_type', 'bank_transfer')
                            ->where('status', 'new_no_paid')->count(); ?>
                        <span class="badge badge-success">{{$new_not_paid_orders}}</span>
                    </a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), 'admin/orders') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-first-order" style="color: aqua;"></i>
                        <span class="title">الطلبات</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  ">
                            <a href="{{url('/admin/orders/new_paid')}}" class="nav-link ">
                                <span class="title"> جديد مدفوع </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/orders/works_on')}}" class="nav-link ">
                                <span class="title">  جاري  العمل علية </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/orders/completed')}}" class="nav-link ">
                                <span class="title">   طلبات مكتملة </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/orders/canceled')}}" class="nav-link ">
                                <span class="title"> طلبات ملغية </span>
                            </a>
                        </li>

                    </ul>
                </li>
            @elseif(auth()->guard('admin')->user()->admin_category_id == 5)
                <li class="nav-item {{ strpos(URL::current(), 'employees/orders') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-first-order" style="color: aqua;"></i>
                        <span class="title">الطلبات</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  ">
                            <a href="{{url('/employees/orders/new_paid')}}" class="nav-link ">
                                <span class="title"> جديد مدفوع </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/employees/orders/works_on')}}" class="nav-link ">
                                <span class="title">  جاري  العمل علية </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/employees/orders/completed')}}" class="nav-link ">
                                <span class="title">   طلبات مكتملة </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/employees/orders/canceled')}}" class="nav-link ">
                                <span class="title"> طلبات ملغية </span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->admin_category_id == 4 )
                <li class="nav-item {{ strpos(URL::current(), 'admin/contacts') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/contacts')}}" class="nav-link ">
                        <i class="fa fa-phone" style="color: aqua;"></i>
                        <span class="title"> تواصل معنا </span>
                        <span class="pull-right-container"></span>
                        <?php $contacts = \App\Models\ContactUs::count(); ?>
                        <span class="badge badge-success">{{$contacts}}</span>
                    </a>
                </li>
            @elseif(auth()->guard('admin')->user()->admin_category_id == 6)
                <li class="nav-item {{ strpos(URL::current(), 'employees/contacts') !== false ? 'active' : '' }}">
                    <a href="{{url('/employees/contacts')}}" class="nav-link ">
                        <i class="fa fa-phone" style="color: aqua;"></i>
                        <span class="title"> تواصل معنا </span>
                        <span class="pull-right-container"></span>
                        <?php $contacts = \App\Models\ContactUs::count(); ?>
                        <span class="badge badge-success">{{$contacts}}</span>
                    </a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), 'admin/banks') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/banks')}}" class="nav-link ">
                        <i class="fa fa-bank" style="color: aqua;"></i>
                        <span class="title"> البنوك </span>
                        <span class="pull-right-container"></span>
                        <span class="badge badge-success">{{\App\Models\Bank::count()}}</span>
                    </a>
                </li>
            @endif

            @if(auth()->guard('admin')->user()->admin_category_id == 4)
                <li class="nav-item {{ strpos(URL::current(), 'admin/sliders') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/sliders')}}" class="nav-link ">
                        <i class="fa fa-sliders" style="color: aqua;"></i>
                        <span class="title"> السلايدر الأعلاني </span>
                        <span class="pull-right-container"></span>
                        <?php $sliders = \App\Models\Slider::count(); ?>
                        <span class="badge badge-success">{{$sliders}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/animated_sliders') !== false ? 'active' : '' }}">
                    <a href="{{url('/admin/animated_sliders')}}" class="nav-link ">
                        <i class="fa fa-sliders" style="color: aqua;"></i>
                        <span class="title"> السلايدر المتحرك </span>
                        <span class="pull-right-container"></span>
                        <span class="badge badge-success">{{\App\Models\AnimatedSlider::count()}}</span>
                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/settings') !== false ? 'active' : '' }}">
                    <a href="{{route('settings')}}" class="nav-link ">
                        <i class="fa fa-cogs" style="color: aqua;"></i>
                        <span class="title"> الأعدادات </span>
                        <span class="pull-right-container">
            </span>

                    </a>
                </li>
                <li class="nav-item {{ strpos(URL::current(), 'admin/pages') !== false ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-cog" style="color: aqua;"></i>
                        <span class="title">الصفحات</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item  ">
                            <a href="{{url('/admin/pages/about')}}" class="nav-link ">
                                <span class="title">من نحن</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{url('/admin/pages/terms')}}" class="nav-link ">
                                <span class="title">الشروط والاحكام</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
