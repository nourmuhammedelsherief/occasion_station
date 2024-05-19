@extends('provider.layouts.master')

@section('title')
    @lang('messages.product_modifiers')
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/provider/home')}}"> لوحه التحكم  </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/provider/provider_product_modifiers/' . $product->id)}}">@lang('messages.product_modifiers')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>اضافه  @lang('messages.product_modifiers')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">اضافه  @lang('messages.product_modifiers') ({{app()->getLocale() == 'ar' ? $product->name : $product->name_en}})
        <small>   @lang('messages.product_modifiers')</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeProviderProductModifier' , $product->id)}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه @lang('messages.product_modifiers')</span>
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
                                                <label class="col-md-3 control-label"> @lang('messages.name_ar') </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name_ar" class="form-control" placeholder="@lang('messages.name_ar')" value="{{old('name_ar')}}" required>
                                                    @if ($errors->has('name_ar'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name_ar') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> @lang('messages.name_en') </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name_en" class="form-control" placeholder="@lang('messages.name_en')" value="{{old('name_en')}}" required>
                                                    @if ($errors->has('name_en'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> تفاصيل نص الإضافة الأساسية (بالعربي)</label>
                                                <div class="col-md-9">
                                                    <textarea name="details_ar" rows="5" class="form-control"
                                                              placeholder="اكتب تفاصيل  نص الإضافة الأساسية باللغة العربية"></textarea>
                                                    @if ($errors->has('details_ar'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('details_ar') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> تفاصيل نص ألإضافة الأساسية (بالانجليزي)</label>
                                                <div class="col-md-9">
                                                    <textarea name="details_en" rows="5" class="form-control"
                                                              placeholder="اكتب تفاصيل  نص ألإضافة الأساسية باللغة الانجليزية"></textarea>
                                                    @if ($errors->has('details_en'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('details_en') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">@lang('messages.count')</label>
                                                <div class="col-md-9">
                                                    <input type="number" name="count" class="form-control" placeholder="@lang('messages.count')" value="{{old('count')}}" required>
                                                    @if ($errors->has('count'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('count') }}</strong>
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

@endsection
