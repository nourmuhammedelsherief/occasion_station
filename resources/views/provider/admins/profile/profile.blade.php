@extends('provider.layouts.master')

@section('title')
    صفحتي  الشخصية
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ url('provider/home') }}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('/provider/profile') }}">صفحتي  الشخصية</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض الصفحة الشخصية</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض الصفحة الشخصية
        <small>تعديل الصفحة الشخصية</small>
    </h1>
@endsection

@section('content')

    @if(session()->has('msg'))

        <p class="alert alert-success" style="width: 100%">

            {{ session()->get('msg') }}

        </p>
    @endif

    <form class="form-horizontal" method="post" action="{{ url('/provider/profileEdit') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-lg-8">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> البيانات الرئيسية</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="btn-group"></div>



                        <div class="form-group">
                            <label for="username" class="col-lg-3 control-label">الاسم</label>
                            <div class="col-lg-9">
                                <input id="username" name="name" type="text" value="{{ $data->name }}" class="form-control" placeholder="الاسم">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-lg-3 control-label">الايميل</label>
                            <div class="col-lg-9">
                                <input id="email" name="email" type="email" value="{{ $data->email }}" class="form-control" placeholder="الايميل">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-lg-3 control-label">الهاتف</label>
                            <div class="col-lg-9">
                                <input id="phone" name="phone_number" type="text" value="{{ $data->phone_number }}" class="form-control" placeholder="الهاتف">
                                @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div style="clear: both"></div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-lg-2 col-lg-offset-10">
                                    {{--<button type="submit" class="btn green btn-block">حفظ</button>--}}
                                    <input class="btn green btn-block" type="submit" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{--{!! Form::close() !!}--}}
@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
@endsection

