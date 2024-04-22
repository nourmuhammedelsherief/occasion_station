@extends('admin.layouts.master')

@section('title')
    إشعارات لأشخاص محددين
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/user_notifications')}}">إشعارات لأشخاص محددين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض إشعارات لأشخاص محددين</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض إشعارات لأشخاص محددين
        <small>اضافة جميع إشعارات لأشخاص محددين</small>
    </h1>
    @include('flash::message')
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeUserNotification')}}" enctype="multipart/form-data">
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
                                                    <select name="user_id[]" class="form-control select2-multiple" required multiple>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}"> {{$user->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('user_id'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('user_id') }}</strong>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "أختر المستخدمين",
                allowClear: true
            });
        });

    </script>
@endsection
