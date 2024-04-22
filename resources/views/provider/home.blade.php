@extends('provider.layouts.master')

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

    <h1 class="page-title">  الإحصائيات
        <small>عرض الإحصائيات</small>
    </h1>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-red" href="{{ url('/provider/products') }}">
                <div class="visual">
                    <i class="fa fa-product-hunt"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$products}}</span>
                    </div>
                    <div class="desc">  المنتجات  </div>
                </div>
            </a>
        </div>
{{--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
{{--            <a class="dashboard-stat dashboard-stat-v2 bg-green" href="{{ url('/provider/orders/new_no_paid') }}">--}}
{{--                <div class="visual">--}}
{{--                    <i class="fa fa-first-order"></i>--}}
{{--                </div>--}}
{{--                <div class="details">--}}
{{--                    <div class="number">--}}
{{--                        <span>{{$orders_notPaid}}</span>--}}
{{--                    </div>--}}
{{--                    <div class="desc"> طلبات بأنتظار التأكيد </div>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-yellow" href="{{ url('/provider/orders/new_paid') }}">
                <div class="visual">
                    <i class="fa fa-first-order"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$orders_paid}}</span>
                    </div>
                    <div class="desc">  طلبات جديده مدفوعه  </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-brown" href="{{ url('/provider/orders/works_on') }}">
                <div class="visual">
                    <i class="fa fa-first-order"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$orders_works}}</span>
                    </div>
                    <div class="desc">  طلبات جاري العمل عليها  </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-grey-cascade" href="{{ url('/provider/orders/completed') }}">
                <div class="visual">
                    <i class="fa fa-first-order"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$orders_completed}}</span>
                    </div>
                    <div class="desc">  طلبات مكتملة  </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 bg-red" href="{{ url('/provider/orders/canceled') }}">
                <div class="visual">
                    <i class="fa fa-first-order"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span>{{$orders_canceled}}</span>
                    </div>
                    <div class="desc">  طلبات ملغية  </div>
                </div>
            </a>
        </div>
{{--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
{{--            <a class="dashboard-stat dashboard-stat-v2 bg-blue" href="{{ url('/provider/commissions') }}">--}}
{{--                <div class="visual">--}}
{{--                    <i class="fa fa-calculator"></i>--}}
{{--                </div>--}}
{{--                <div class="details">--}}
{{--                    <div class="number">--}}
{{--                        <span>{{$commissions}}</span>--}}
{{--                    </div>--}}
{{--                    <div class="desc"> عمولاتي </div>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}

    </div>
@endsection
