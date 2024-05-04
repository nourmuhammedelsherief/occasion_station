@extends('admin.layouts.master')

@section('title')
    الأقسام الرئيسيه
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}"> لوحه التحكم  </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/categories')}}">الأقسام الرئيسيه</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  الأقسام الرئيسيه</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  الأقسام الرئيسيه
        <small>   الأقسام الرئيسيه</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeCategory')}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه الأقسام الرئيسيه</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered table-responsive">
                                <div class="portlet-body form">
                                    <div class="form-horizontal" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">القسم بالعربي </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control" placeholder="أكتب أسم القسم باللغة العربية" value="{{old('name')}}" required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">القسم بالانجليزي </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name_en" class="form-control" placeholder="أكتب أسم القسم باللغة الانجليزية" value="{{old('name_en')}}" required>
                                                    @if ($errors->has('name_en'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> أيقونه القسم </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">

                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> أخترالأيقونه </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="icon"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput"> إزالة </a>


                                                            </div>
                                                        </div>
                                                        @if ($errors->has('icon'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('icon') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

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
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>
                        </div>
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
