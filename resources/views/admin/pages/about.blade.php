@extends('admin.layouts.master')

@section('title')
    من نحن
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
                <a href="/admin/pages/about">من نحن</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل من نحن</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> من نحن
        <small>تعديل من نحن</small>
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
            <form action="{{url('admin/add/pages/about')}}" method="post">
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
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">العنوان بالعربي</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="العنوان بالعربي"
                                                       name="title" value="{{$settings->title}}">
                                                @if ($errors->has('title'))
                                                    <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">العنوان بالانجليزي</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="العنوان بالانجليزي"
                                                       name="title_en" value="{{$settings->title_en}}">
                                                @if ($errors->has('title_en'))
                                                    <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('title_en') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">المحتوي بالعربي</label>
                                            <div class="col-md-9">
                                                <textarea type="text" rows="10" class="form-control"
                                                          placeholder="المحتوي بالعربي"
                                                          name="content">{{$settings->content}}</textarea>
                                                @if ($errors->has('content'))
                                                    <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('content') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> المحتوي بالانجليزي</label>
                                            <div class="col-md-9">
                                                <textarea type="text" rows="10" class="form-control"
                                                          placeholder="المحتوي بالانجليزي"
                                                          name="content_en">{{$settings->content_en}}</textarea>
                                                @if ($errors->has('content_en'))
                                                    <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('content_en') }}</strong>
                                                    </span>
                                                @endif
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

@section('scripts')

    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script>

        CKEDITOR.replace('description2');
    </script>




@endsection
