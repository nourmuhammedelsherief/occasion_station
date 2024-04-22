@extends('admin.layouts.master')

@section('title')
    السلايدر الأعلاني
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
                <a href="{{url('/admin/sliders')}}">السلايدر الأعلاني</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  السلايدر الأعلاني</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  السلايدر الأعلاني
        <small>   السلايدر الأعلاني</small>
    </h1>
@endsection

@section('content')

    <div class="row">
        @include('flash::message')
        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('storeSlider')}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه السلايدر الأعلاني</span>
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
                                                <label class="col-md-3 control-label"> السلايدر الأعلاني </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title" class="form-control" placeholder="أكتب عنوان السلايدر الأعلاني" value="{{old('title')}}" required>
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <h5 class="text-center" style="color: red"> قم بأضافه مزود أو منتج أو رابط  خارجي(واحد فقط) </h5>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> اختر مزود  </label>
                                                <div class="col-md-9">
                                                    <select name="provider_id" class="form-control">
                                                        <option disabled selected> اختر مزود  </option>
                                                        @foreach($providers as $provider)
                                                            <option value="{{$provider->id}}"> {{$provider->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('provider_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('provider_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> اختر منتج  </label>
                                                <div class="col-md-9">
                                                    <select name="product_id" class="form-control">
                                                        <option disabled selected> اختر منتج  </option>
                                                        @foreach($products as $product)
                                                            <option value="{{$product->id}}"> {{$product->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('product_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('product_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">  رابط خارجي للسلايدر </label>
                                                <div class="col-md-9">
                                                    <input type="url" name="outer_url" class="form-control" placeholder="أدخل رابط خارجي للسلايدر في حاله ليس هناك  مزود أو  منتج" value="{{old('outer_url')}}" required>
                                                    @if ($errors->has('outer_url'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('outer_url') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label class="control-label col-md-3"> صورة السلايدر</label>
                                                <div class="col-md-9">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                        </div>
                                                        <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="photo"> </span>
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
