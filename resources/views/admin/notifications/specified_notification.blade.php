@extends('admin.layouts.master')

@section('title')
    إشعارات لفئة معينه
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/specified_notification')}}">إشعارات لفئة معينه</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض إشعارات لفئة معينه</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض إشعارات لفئة معينه
        <small>اضافة جميع إشعارات لفئة معينه</small>
    </h1>
    @include('flash::message')
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeSpecified_notification')}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-body">
                        <div class="row">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered table-responsive">
                                <div class="portlet-body form">
                                    <div class="form-horizontal" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> إشعار الي </label>
                                                <div class="col-md-9">
                                                    <select name="type" class="form-control" required>
                                                        <option disabled selected> اختر نوع المستخدمين </option>
                                                        <option value="users"> العملاء</option>
                                                        <option value="providers"> المزودين</option>
                                                    </select>
                                                    @if ($errors->has('ar_title'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('ar_title') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">العنوان </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title" class="form-control"
                                                           placeholder="أكتب عنوان الاشعار " value="{{old('title')}}">
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">محتوي الإشعار </label>
                                                <div class="col-md-9">
                                                    <textarea name="message" class="form-control"
                                                              placeholder="أكتب  محتوي  الإشعار "></textarea>
                                                    @if ($errors->has('message'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('message') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn green" value="حفظ"
                                                            onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">
                                                        حفظ
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->


                        </div>


                        <!-- END CONTENT BODY -->

                        <!-- END CONTENT -->


                    </div>
                </div>

            </form>
            <!-- END TAB PORTLET-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection
