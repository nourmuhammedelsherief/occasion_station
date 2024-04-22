@extends('admin.layouts.master')

@section('title')
    ترتيب المزودين داخل الأقسام
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
                <a href="{{url('/admin/providers')}}">ترتيب المزودين داخل الأقسام</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  ترتيب المزودين داخل الأقسام</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> ({{$category->category->name}})  ترتيب المزودين داخل القسم
        <small>   ترتيب المزودين داخل الأقسام</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('submitArrangeProvider' , $category->id)}}" enctype="multipart/form-data" >
                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه ترتيب المزودين داخل الأقسام</span>
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
                                                <label class="control-label"> ترتيب المزود </label>
                                                <select name="arrange" class="form-control" required>
                                                    <option disabled selected> اختر ترتيب المزود داخل القسم </option>
                                                    @for($i=1; $i <= \App\Models\ProviderMainCategory::whereCategoryId($category->category->id)->count()+2; $i++)
                                                        <?php $check = \App\Models\ProviderMainCategory::whereArrange($i)
                                                            ->where('category_id' , $category->category->id)
                                                            ->where('provider_id' , '!=', $category->provider->id)
                                                            ->first();
                                                        ?>
                                                        @if($check == null)
                                                            <option value="{{$i}}" {{$i == $category->arrange ? 'selected' : ''}}> {{$i}} </option>
                                                        @endif
                                                    @endfor
                                                </select>
                                                @if ($errors->has('arrange'))
                                                    <span class="help-block">
                                                        <strong style="color: red;">{{ $errors->first('arrange') }}</strong>
                                                    </span>
                                                @endif
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
