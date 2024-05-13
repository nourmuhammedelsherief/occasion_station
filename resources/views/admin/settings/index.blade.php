@extends('admin.layouts.master')

@section('title')
    اعدادات الموقع
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/setting">اعدادات الموقع</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل اعدادات الموقع</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> اعدادات الموقع
        <small>تعديل اعدادات الموقع</small>
    </h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            @if(count($errors))
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{url('admin/add/settings')}}" method="post">
                <input type='hidden' name='_token' value='{{Session::token()}}'>

                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->

                    <div class="row">
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <div class="portlet light bordered table-responsive">
                            <div class="portlet-body form">
                                <div class="form-horizontal" role="form">
                                    <div class="form-body">
                                        <!--<div class="form-group">-->
                                        <!--    <label class="col-md-3 control-label"> عمولة التطبيق </label>-->
                                        <!--    <div class="col-md-7">-->
                                    <!--        <input type="text" class="form-control" placeholder="أدخل قيمه العموله للمزودين" name="commission" value="{{$settings->commission}}">-->
                                        <!--    </div>-->
                                        <!--    <div class="col-md-2">-->
                                        <!--        %-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> الوقت المحدد لألغاء  الطلب </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="الوقت المحدد لألغاء  الطلب" name="order_cancel_time" value="{{$settings->order_cancel_time}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        دقيقة--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> الوقت المحدد لقبول الطلب </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="الوقت المحدد لقبول الطلب" name="order_accept_time" value="{{$settings->order_accept_time}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        دقيقة--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> نطاق أرسال  الطللبات </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="نطاق أرسال الطلبات" name="search_range" value="{{$settings->search_range}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        كيلومتر--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> نطاق بحث  المطاعم </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="نطاق بحث  المطاعم" name="search_restaurants" value="{{$settings->search_restaurants}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        كيلومتر--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> المرسل </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="المرسل"
                                                       name="bearer_token" value="{{$settings->bearer_token}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> التوكن </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="التوكن"
                                                       name="sender_name" value="{{$settings->sender_name}}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> رقم تواصل خدمة العملاء </label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" placeholder=" رقم تواصل خدمة العملاء "
                                                       name="customer_services_number" value="{{$settings->customer_services_number}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> رقم التواصل </label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" placeholder="رقم  التواصل"
                                                       name="contact_number" value="{{$settings->contact_number}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> رقم تواصل (مستشارك) </label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control"
                                                       placeholder="أضف رقم التواصل الخاص بأيقونه مستشارك"
                                                       name="advisor_number" value="{{$settings->advisor_number}}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">النص المستخدم للتواصل مع
                                                (مستشارك) </label>
                                            <div class="col-md-9">
                                                <textarea name="contact_text" class="form-control"
                                                          rows="3">{{$settings->contact_text}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> سعر التوصيل </label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control"
                                                       placeholder="أدخل سعر التوصيل للمنتجات"
                                                       name="delivery_price" value="{{$settings->delivery_price}}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> الضريبة </label>
                                            <div class="col-md-7">
                                                <input type="number" class="form-control"
                                                       placeholder="ادخل قيمه الضريبة المضافة" name="tax"
                                                       value="{{$settings->tax}}">
                                            </div>
                                            <div class="col-sm-2"> %</div>
                                        </div>

                                        {{--                                        <div class="form-group">--}}
                                        {{--                                            <label class="col-md-3 control-label">  نطاق البحث </label>--}}
                                        {{--                                            <div class="col-md-9">--}}
                                        {{--                                                <input type="number" class="form-control" placeholder="أدخل نطاق البحث  عن موظفين" name="search_range" value="{{$settings->search_range}}">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> توكن ماي فاتورة </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control"
                                                       placeholder="أدخل التوكن الخاص بالدفع الاونلاين لماي فاتورة"
                                                       name="myFatoourah_token"
                                                       value="{{$settings->myFatoourah_token}}">
                                            </div>
                                        </div>
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> نطاق أرسال  الطللبات </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="نطاق أرسال الطلبات" name="search_range" value="{{$settings->search_range}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        كيلومتر--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> عمولة التطبيق   </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="عمولة  السائقين" name="driver_commission" value="{{$settings->driver_commission}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        %--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> الحد الاقصي للعمولات المستحقة   </label>--}}
                                        {{--                                    <div class="col-md-7">--}}
                                        {{--                                        <input type="number" class="form-control" placeholder="الحد الاقصي للعمولات المستحقة" name="commission_limit" value="{{$settings->commission_limit}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                    <div class="col-md-2">--}}
                                        {{--                                        ريال ,جنية,....--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}

                                        {{--                                <h2 class="text-center"> بيانات الرسايل sms </h2>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> التوكن (Bearer Token) </label>--}}
                                        {{--                                    <div class="col-md-9">--}}
                                        {{--                                        <input type="text" class="form-control" placeholder="التوكن (Bearer Token)" name="bearer_token" value="{{$settings->bearer_token}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        {{--                                <div class="form-group">--}}
                                        {{--                                    <label class="col-md-3 control-label"> اسم  المرسل (Sender Name) </label>--}}
                                        {{--                                    <div class="col-md-9">--}}
                                        {{--                                        <input type="text" class="form-control" placeholder="اسم  المرسل (Sender Name)" name="sender_name" value="{{$settings->sender_name}}">--}}
                                        {{--                                    </div>--}}
                                        {{--                                </div>--}}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> البريد الإلكتروني لأستقال أشعارات الطلبات  </label>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control"
                                                       placeholder="ادخل البريد الإلكتروني لأستقال أشعارات الطلبات"  name="email"
                                                value="{{$settings->email}}">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- END SAMPLE FORM PORTLET-->


                    </div>


                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->


                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END TAB PORTLET-->

        </div>
    </div>

@endsection

