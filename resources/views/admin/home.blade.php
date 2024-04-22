@extends('admin.layouts.master')

@section('title')
    لوحة التحكم
@endsection

@section('content')

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}"> لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>الإحصائيات</span>
            </li>
        </ul>
    </div>

    <h3 style="color: green">
        أهلا بك {{auth()->guard('admin')->user()->name}}
        <small> القسم التابع لة : {{auth()->guard('admin')->user()->category->name}} </small>
    </h3>
    <h1 class="page-title"> الإحصائيات
        <small>عرض الإحصائيات</small>
    </h1>

    <div class="row">
        @if(auth()->guard('admin')->user()->admin_category_id == 4)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-blue" href="{{ url('/admin/admins') }}">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$admins}}</span>
                        </div>
                        <div class="desc"> عدد المديرين</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-red" href="{{ url('/admin/users/1') }}">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$users}}</span>
                        </div>
                        <div class="desc"> عدد العملاء</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green" href="{{ url('/admin/categories') }}">
                    <div class="visual">
                        <i class="fa fa-list-alt"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$main_categories}}</span>
                        </div>
                        <div class="desc"> الأقسام الرئيسية</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-yellow" href="{{ url('/admin/sub_categories') }}">
                    <div class="visual">
                        <i class="fa fa-list-alt"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$sub_categories}}</span>
                        </div>
                        <div class="desc"> الأقسام الفرعية</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-brown" href="{{ url('/admin/cities') }}">
                    <div class="visual">
                        <i class="fa fa-flag-checkered"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$cities}}</span>
                        </div>
                        <div class="desc"> المدن</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-blue" href="{{ url('/admin/products') }}">
                    <div class="visual">
                        <i class="fa fa-product-hunt"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$products}}</span>
                        </div>
                        <div class="desc"> المنتجات</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-yellow-gold-opacity" href="{{ url('/admin/sliders') }}">
                    <div class="visual">
                        <i class="fa fa-sliders"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$sliders}}</span>
                        </div>
                        <div class="desc"> السلايدر الأعلاني</div>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->guard('admin')->user()->admin_category_id == 4)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-grey-cascade" href="{{ url('/admin/providers') }}">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$providers}}</span>
                        </div>
                        <div class="desc"> المزودين</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green-dark"
                   href="{{ url('/admin/new_not_paid_orders') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$orders_wait_pay}}</span>
                        </div>
                        <div class="desc"> طلبات بأنتظار تأكيد الدفع</div>
                    </div>
                </a>
            </div>
        @elseif(auth()->guard('admin')->user()->admin_category_id == 3)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-grey-cascade" href="{{ url('/employees/providers') }}">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$providers}}</span>
                        </div>
                        <div class="desc"> المزودين</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green-dark"
                   href="{{ url('/employees/new_not_paid_orders') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$orders_wait_pay}}</span>
                        </div>
                        <div class="desc"> طلبات بأنتظار تأكيد الدفع</div>
                    </div>
                </a>
            </div>
        @endif
        @if(auth()->guard('admin')->user()->admin_category_id == 4)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green-haze" href="{{ url('/admin/orders/new_paid') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$paid_orders}}</span>
                        </div>
                        <div class="desc"> طلبات جديده مدفوعه</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-yellow-gold" href="{{ url('/admin/orders/works_on') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$works_orders}}</span>
                        </div>
                        <div class="desc"> طلبات جاري العمل عليها</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green" href="{{ url('/admin/orders/completed') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$completed_orders}}</span>
                        </div>
                        <div class="desc"> طلبات مكتملة</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-red-pink" href="{{ url('/admin/orders/canceled') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$canceled_orders}}</span>
                        </div>
                        <div class="desc"> طلبات ملغية</div>
                    </div>
                </a>
            </div>
        @elseif( auth()->guard('admin')->user()->admin_category_id == 5)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green-haze"
                   href="{{ url('/employees/orders/new_paid') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$paid_orders}}</span>
                        </div>
                        <div class="desc"> طلبات جديده مدفوعه</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-yellow-gold"
                   href="{{ url('/employees/orders/works_on') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$works_orders}}</span>
                        </div>
                        <div class="desc"> طلبات جاري العمل عليها</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-green" href="{{ url('/employees/orders/completed') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$completed_orders}}</span>
                        </div>
                        <div class="desc"> طلبات مكتملة</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-red-pink" href="{{ url('/employees/orders/canceled') }}">
                    <div class="visual">
                        <i class="fa fa-first-order"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$canceled_orders}}</span>
                        </div>
                        <div class="desc"> طلبات ملغية</div>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->guard('admin')->user()->admin_category_id == 4)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-brown-800" href="{{ url('/admin/contacts') }}">
                    <div class="visual">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$contacts}}</span>
                        </div>
                        <div class="desc"> أتصل بنا</div>
                    </div>
                </a>
            </div>
        @elseif(auth()->guard('admin')->user()->admin_category_id == 6)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 bg-brown-800" href="{{ url('/employees/contacts') }}">
                    <div class="visual">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{$contacts}}</span>
                        </div>
                        <div class="desc"> أتصل بنا</div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endsection
