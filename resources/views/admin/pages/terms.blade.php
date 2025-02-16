@extends('admin.layouts.master')

@section('title')
    الشروط والاحكام
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
                <a href="{{url('/admin/pages/terms')}}">الشروط والاحكام</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل الشروط والاحكام</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> الشروط والاحكام
        <small>تعديل الشروط والاحكام</small>
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
            <form action="{{url('admin/add/pages/terms')}}" method="post">
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
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">العنوان بالانجليزي</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="العنوان بالانجليزي"
                                                       name="title_en" value="{{$settings->title_en}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">المحتوي بالعربي</label>
                                            <div class="col-md-9">
                                                <textarea type="text" rows="10" class="form-control"
                                                          placeholder="المحتوي بالعربي"
                                                          name="content">{{$settings->content}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">المحتوي بالانجليزي</label>
                                            <div class="col-md-9">
                                                <textarea type="text" rows="10" class="form-control"
                                                          placeholder="المحتوي بالانجليزي"
                                                          name="content_en">{{$settings->content_en}}</textarea>
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
