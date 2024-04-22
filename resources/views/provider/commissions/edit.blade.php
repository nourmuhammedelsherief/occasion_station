@extends('provider.layouts.master')

@section('title')
    عمولاتي
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/provider/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/provider/commissions')}}">عمولاتي</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  عمولاتي</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض عمولاتي
        <small> عمولاتي</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateMyCommission', $commission->id)}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> تعديل عمولاتي</span>
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
                                                <label class="col-md-3 control-label"> المبلغ </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="amount" class="form-control"
                                                           placeholder="أدخل مبلغ العموله" value="{{$commission->amount}}"
                                                           required>
                                                    @if ($errors->has('amount'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('amount') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صوره التحويل البنكي </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($commission->transfer_photo != null)
                                                                    <img src="{{asset('/uploads/transfers/' . $commission->transfer_photo)}}">
                                                                @endif
                                                            </div>
                                                            <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="transfer_photo"> </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('photo'))
                                                            <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('photo') }}</strong>
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
                            <button type="submit" class="btn green" value="حفظ"
                                    onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">
                                حفظ
                            </button>
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
        CKEDITOR.replace('description1');
    </script>
    <script>
        CKEDITOR.replace('description2');
    </script>
    <script>
        $(document).ready(function () {
            $("input[name=delivery]").change(function(){

                if($("#yes").is(':checked')){
                    $("#delivery_price").show();
                }else{
                    $("#delivery_price").hide();
                }
            });
        });
    </script>
@endsection
