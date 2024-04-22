<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item start active open" >
                <a href="/provider/home" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">الرئيسية</span>
                    <span class="selected"></span>

                </a>
            </li>
            <li class="heading">
                <h3 class="uppercase">القائمة الجانبية</h3>
            </li>
            <li class="nav-item {{ strpos(URL::current(), 'provider/profile') !== false ? 'active' : '' }}">
                <a href="{{url('/provider/profile')}}" class="nav-link ">
                    <i class="fa fa-user" style="color: aqua;"></i>
                    <span class="title"> الصفحه الشخصية </span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <li class="nav-item {{ strpos(URL::current(), '/provider/products') !== false ? 'active' : '' }}">
                <a href="{{url('/provider/products')}}" class="nav-link ">
                    <i class="fa fa-product-hunt" style="color: aqua;"></i>
                    <span class="title">  منتجاتي </span>
                    <span class="pull-right-container"></span>
                    @if(Auth::guard('provider')->user() != null)
                        <?php $products = \App\Models\Product::whereProviderId(\Illuminate\Support\Facades\Auth::guard('provider')->user()->id)->count(); ?>
                        <span class="badge badge-success">{{$products}}</span>
                    @endif
                </a>
            </li>
{{--            <li class="nav-item {{ strpos(URL::current(), '/provider/orders/new_no_paid') !== false ? 'active' : '' }}">--}}
{{--                <a href="{{url('/provider/orders/new_no_paid')}}" class="nav-link ">--}}
{{--                    <i class="fa fa-first-order" style="color: aqua;"></i>--}}
{{--                    <span class="title">  طلبات  بأنتظار تأكيد الدفع  </span>--}}
{{--                    <span class="pull-right-container"></span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-item {{ strpos(URL::current(), 'provider/orders') !== false ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-first-order" style="color: aqua;"></i>
                    <span class="title">الطلبات</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    <li class="nav-item  ">
                        <a href="{{url('/provider/orders/new_paid')}}" class="nav-link ">
                            <span class="title"> جديد مدفوع </span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{url('/provider/orders/works_on')}}" class="nav-link ">
                            <span class="title">  جاري  العمل علية </span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{url('/provider/orders/completed')}}" class="nav-link ">
                            <span class="title">   طلبات مكتملة </span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{url('/provider/orders/canceled')}}" class="nav-link ">
                            <span class="title"> طلبات ملغية </span>
                        </a>
                    </li>

                </ul>
            </li>

        <!--<li class="nav-item {{ strpos(URL::current(), '/provider/commissions') !== false ? 'active' : '' }}">-->
        <!--    <a href="{{url('/provider/commissions')}}" class="nav-link ">-->
            <!--        <i class="fa fa-calculator" style="color: aqua;"></i>-->
            <!--        <span class="title">  عمولاتي </span>-->
            <!--        <span class="pull-right-container"></span>-->
        <!--        @if(Auth::guard('provider')->user() != null)-->
        <!--            <?php $commissions = \App\Models\ProviderCommissionHistory::whereProviderId(\Illuminate\Support\Facades\Auth::guard('provider')->user()->id)->count(); ?>-->
        <!--            <span class="badge badge-success">{{$commissions}}</span>-->
            <!--        @endif-->
            <!--    </a>-->
            <!--</li>-->

            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/settings') !== false ? 'active' : '' }}">--}}
            {{--                <a href="{{route('settings')}}" class="nav-link ">--}}
            {{--                    <i class="fa fa-cogs" style="color: aqua;"></i>--}}
            {{--                    <span class="title"> الأعدادات </span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--            </span>--}}

            {{--                </a>--}}
            {{--            </li>--}}


            {{--            <li class="nav-item {{ strpos(URL::current(), 'admin/pages') !== false ? 'active' : '' }}">--}}
            {{--                <a href="javascript:;" class="nav-link nav-toggle">--}}
            {{--                    <i class="fa fa-cog" style="color: aqua;"></i>--}}
            {{--                    <span class="title">الصفحات</span>--}}
            {{--                    <span class="arrow"></span>--}}
            {{--                </a>--}}
            {{--                <ul class="sub-menu">--}}
            {{--                    <li class="nav-item  ">--}}
            {{--                        <a href="{{url('/admin/pages/about')}}" class="nav-link ">--}}
            {{--                            <span class="title">من نحن</span>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item  ">--}}
            {{--                        <a href="{{url('/admin/pages/terms')}}" class="nav-link ">--}}
            {{--                            <span class="title">الشروط والاحكام</span>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}




            {{--                </ul>--}}
            {{--            </li>--}}



        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
