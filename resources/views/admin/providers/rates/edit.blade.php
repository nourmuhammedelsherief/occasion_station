@extends('admin.layouts.master')

@section('title')
    تقييمات المزود
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
                <a href="{{url('/admin/cities')}}">تقييمات المزود</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  تقييمات المزود</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  تقييمات المزود
        ({{$rate->provider->name}})
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateProviderRates' , $rate->id)}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> تعديل تقييمات المزود</span>
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
                                                <label class="col-md-3 control-label"> العميل </label>
                                                <div class="col-md-9">
                                                    <select name="user_id" class="form-control" required>
                                                        <option disabled selected> اختر العميل </option>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}" {{$rate->user_id == $user->id ? 'selected' : ''}}> {{$user->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('rate'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('rate') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> التقييم </label>
                                                <div class="col-md-9">
                                                    <select name="rate" class="form-control" required>
                                                        <option disabled selected> اختر التقييم للمزود </option>
                                                        <option value="1" {{$rate->rate == 1 ? 'selected' : ''}}>1</option>
                                                        <option value="2" {{$rate->rate == 2 ? 'selected' : ''}}>2</option>
                                                        <option value="3" {{$rate->rate == 3 ? 'selected' : ''}}>3</option>
                                                        <option value="4" {{$rate->rate == 4 ? 'selected' : ''}}>4</option>
                                                        <option value="5" {{$rate->rate == 5 ? 'selected' : ''}}>5</option>
                                                    </select>
                                                    @if ($errors->has('rate'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('rate') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> نص التقييم </label>
                                                <div class="col-md-9">
                                                    <textarea name="rate_text" class="form-control" rows="5" cols="5">{{$rate->rate_text}}</textarea>
                                                    @if ($errors->has('rate'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('rate') }}</strong>
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
