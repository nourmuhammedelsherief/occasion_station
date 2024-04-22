@extends('admin.layouts.master')

@section('title')
    أضافة ملاحظه للطلب
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
                <a href="{{route('showOrder' , $order->id)}}">  تفاصيل الطلب</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  أضافة ملاحظه للطلب</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  أضافة ملاحظه للطلب
        <small>   أضافة ملاحظه للطلب</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeAdminNote' , $order->id)}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه  ملاحظه للطلب</span>
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
                                                <label class="col-md-3 control-label"> اضف ملاحظتك علي  الطلب </label>
                                                <div class="col-md-9">
                                                    <textarea id="description2" name="notes" ></textarea>
                                                    @if ($errors->has('notes'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('notes') }}</strong>
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
    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script>

        CKEDITOR.replace('description2');
    </script>
@endsection
