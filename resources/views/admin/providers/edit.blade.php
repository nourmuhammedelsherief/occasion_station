@extends('admin.layouts.master')

@section('title')
    مزودي الخدمات
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
          integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        #map {
            height: 500px;
            width: 1000px;
        }
    </style>
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/providers')}}">مزودي الخدمات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  مزودي الخدمات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض مزودي الخدمات
        <small> مزودي الخدمات</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateProvider' , $provider->id)}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> تعديل مزودي الخدمات</span>
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
                                                <label class="col-md-3 control-label"> المدينه </label>
                                                <div class="col-md-9">
                                                    <select name="city_id" class="form-control" required>
                                                        <option disabled selected> أختر مدينه المزود</option>
                                                        @foreach($cities as $city)
                                                            <option
                                                                value="{{$city->id}}" {{$city->id == $provider->city_id ? 'selected' : ''}}> {{$city->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">اسم المزود بالعربي</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control"
                                                           placeholder="أكتب أسم  المزود باللغة العربية" value="{{$provider->name}}"
                                                           required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">اسم المزود بالانجليزي </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name_en" class="form-control"
                                                           placeholder="أكتب أسم  المزود باللغة ألانجليزية" value="{{$provider->name_en}}"
                                                           required>
                                                    @if ($errors->has('name_en'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> البريد الإلكتروني </label>
                                                <div class="col-md-9">
                                                    <input type="email" name="email" class="form-control"
                                                           placeholder="البريد الإلكتروني للمزود"
                                                           value="{{$provider->email}}" required>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> رقم الهاتف </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="phone_number" class="form-control"
                                                           placeholder=" رقم الهاتف للمزود"
                                                           value="{{$provider->phone_number}}" required>
                                                    @if ($errors->has('phone_number'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--<div class="form-group">-->
                                            <!--    <label class="col-md-3 control-label">عنوان المزود </label>-->
                                            <!--    <div class="col-md-9">-->
                                            <!--        <input type="text" name="address" class="form-control"-->
                                            <!--               placeholder="أكتب عنوان  المزود"-->
                                        <!--               value="{{$provider->address}}"-->
                                            <!--               required>-->
                                        <!--        @if ($errors->has('address'))-->
                                            <!--            <span class="help-block">-->
                                        <!--   <strong style="color: red;">{{ $errors->first('address') }}</strong>-->
                                            <!--</span>-->
                                            <!--        @endif-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> كلمه المرور </label>
                                                <div class="col-md-9">
                                                    <input type="password" name="password" class="form-control"
                                                           placeholder="الرقم السري للمزود" value="{{old('password')}}"
                                                           required>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">أعاده كلمه المرور </label>
                                                <div class="col-md-9">
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control" placeholder="أعاده الرقم السري للمزود"
                                                           value="{{old('password_confirmation')}}" required>
                                                    @if ($errors->has('password_confirmation'))
                                                        <span class="help-block">
                                               <strong
                                                   style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> النشاط </label>
                                                <div class="col-md-9">
                                                    <select name="activity" class="form-control" required>
                                                        <option disabled selected> أختر النشاط للمزود</option>
                                                        <option
                                                            value="sale" {{$provider->activity == 'sale' ? 'selected' : ''}}>
                                                            بيع
                                                        </option>
                                                        <option
                                                            value="rent" {{$provider->activity == 'rent' ? 'selected' : ''}}>
                                                            تأجير
                                                        </option>
                                                        <option
                                                            value="both" {{$provider->activity == 'both' ? 'selected' : ''}}>
                                                            كلاهما
                                                        </option>
                                                    </select>
                                                    @if ($errors->has('activity'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('activity') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($main_categories->count() > 0)
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">
                                                        الاقسام الرئيسية للمزود
                                                    </label>
                                                    {{--                                                        @foreach($main_categories as $main_category)--}}
                                                    {{--                                                            <div class="col-md-3 test text-center col-sm-4 cat_{{ $main_category->id }}">--}}
                                                    {{--                                                                <p>--}}
                                                    {{--                                                                    {{$main_category->category->name}}--}}
                                                    {{--                                                                </p>--}}
                                                    {{--                                                                <a  id="{{ $main_category->id }}"  style="color: white;text-decoration: none;" class="delete_main_cat hideDiv btn btn-danger btn-sm">--}}
                                                    {{--                                                                    <i class="glyphicon glyphicon-trash "></i> مسح</a>--}}
                                                    {{--                                                            </div>--}}
                                                    {{--                                                        @endforeach--}}
                                                    <div class="col-md-9">
                                                        <select name="category_id" class="form-control" required>
                                                            <option disabled selected> أختر القسم الرئيسي للمزود
                                                            </option>
                                                            @foreach($categories  as $category)
                                                                <option
                                                                    value="{{$category->id}}" {{\App\Models\ProviderMainCategory::whereProviderId($provider->id)->where('category_id' , $category->id)->first() != null ? 'selected' : ''}}> {{$category->name}} </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('category_id'))
                                                            <span class="help-block">
                                                                <strong
                                                                    style="color: red;">{{ $errors->first('category_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> حدد نوع الدفع للمزود </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" name="bank_payment" value="true" {{$provider->bank_payment == 'true' ? 'checked' : ''}} style="margin: 5px"> تحويل بنكي

                                                    <input type="checkbox" name="online_payment" value="true" {{$provider->online_payment == 'true' ? 'checked' : ''}} style="margin: 5px"> دفع اونلاين

                                                    <input type="checkbox" name="tamara_payment" value="true" {{$provider->tamara_payment == 'true' ? 'checked' : ''}} style="margin: 5px"> دفع تمارا
                                                </div>
                                            </div>
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label class="col-md-3 control-label"> القسم الرئيسي </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <select name="category_id[]" class="form-control select2-multiple" required multiple>--}}
                                            {{--                                                        --}}{{--                                                        <option disabled selected> أختر القسم الرئيسي للمزود</option>--}}
                                            {{--                                                        @foreach($categories  as $category)--}}
                                            {{--                                                            <option--}}
                                            {{--                                                                value="{{$category->id}}"> {{$category->name}} </option>--}}
                                            {{--                                                        @endforeach--}}
                                            {{--                                                    </select>--}}
                                            {{--                                                    @if ($errors->has('category_id'))--}}
                                            {{--                                                        <span class="help-block">--}}
                                            {{--                                               <strong style="color: red;">{{ $errors->first('category_id') }}</strong>--}}
                                            {{--                                            </span>--}}
                                            {{--                                                    @endif--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}

                                            {{--                                            @if($sub_categories->count() > 0)--}}
                                            {{--                                                <div class="row">--}}
                                            {{--                                                    <div class="form-group">--}}
                                            {{--                                                        <label class="col-md-3 control-label col-md-3">--}}
                                            {{--                                                            الاقسام الفرعية للمزود--}}
                                            {{--                                                        </label>--}}
                                            {{--                                                        @foreach($sub_categories as $sub_category)--}}
                                            {{--                                                            <div class="col-md-3 test text-center col-sm-4 subCat_{{ $sub_category->id }}">--}}
                                            {{--                                                                <p>--}}
                                            {{--                                                                    {{$sub_category->sub_category->name}}--}}
                                            {{--                                                                </p>--}}
                                            {{--                                                                <a  id="{{ $sub_category->id }}"  style="color: white;text-decoration: none;" class="delete_sub_cat hideDiv btn btn-danger btn-sm">--}}
                                            {{--                                                                    <i class="glyphicon glyphicon-trash "></i> مسح</a>--}}
                                            {{--                                                            </div>--}}
                                            {{--                                                        @endforeach--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            @endif--}}
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label class="col-md-3 control-label" for="select2Multiple"> الأقسام الفرعية </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <select  class="select2-multiple form-control" name="sub_categories[]" multiple="multiple" id="select2Multiple">--}}
                                            {{--                                                        --}}{{--                                                        @if($provider->category->sub_categories->count() > 0)--}}
                                            {{--                                                        --}}{{--                                                            @foreach($provider->category->sub_categories as $cat)--}}
                                            {{--                                                        --}}{{--                                                                <option value="{{$cat->id}}">{{$cat->name}}</option>--}}
                                            {{--                                                        --}}{{--                                                            @endforeach--}}
                                            {{--                                                        --}}{{--                                                        @endif--}}

                                            {{--                                                    </select>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">هل السعر شامل التوصيل ؟</label>
                                                <div class="col-md-9">
                                                    <input type="radio" name="delivery" {{$provider->delivery == 'false' ? 'checked' : ''}} value="false"> نعم
                                                    <input type="radio" name="delivery" {{$provider->delivery == 'true' ? 'checked' : ''}} id="provider" value="true"> لا
                                                    @if ($errors->has('delivery'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('delivery') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group" id="delivery_by" style="{{$provider->delivery == 'false' ? 'display: none' : 'display: block'}}">
                                                <label class="col-md-3 control-label">من سيقوم بالتوصيل</label>
                                                <div class="col-md-9">
                                                    <input type="radio" name="delivery_by" {{$provider->delivery_by == 'provider' ? 'checked' : ''}} value="provider">  المزود من سيقوم بالتوصيل
                                                    <input type="radio" name="delivery_by" {{$provider->delivery_by == 'app' ? 'checked' : ''}} id="yes" value="app"> التوصيل عن طريق التطبيق
                                                    @if ($errors->has('delivery_by'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('delivery_by') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div id="delivery_price" style="{{ ($provider->delivery == 'true' and $provider->delivery_by == 'app') ? 'display: block' : 'display: none'}}">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">أستلام المنتج من المتجر</label>
                                                    <div class="col-md-9">
                                                        <input type="radio" name="store_receiving" {{$provider->store_receiving == 'true' ? 'checked' : ''}}  value="true"> نعم
                                                        <input type="radio" name="store_receiving" {{$provider->store_receiving == 'false' ? 'checked' : ''}} value="false"> لا
                                                        @if ($errors->has('store_receiving'))
                                                            <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('store_receiving') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">سعر التوصيل</label>
                                                    <div class="col-md-9">
                                                        <input type="number" name="delivery_price" class="form-control" placeholder="ادخل سعر التوصيل للمزود" value="{{$provider->delivery_price}}">
                                                        @if ($errors->has('delivery_price'))
                                                            <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('delivery_price') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صوره المزود </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                                <img
                                                                    src="{{asset('/uploads/providers/' . $provider->photo)}}">
                                                            </div>
                                                            <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new"> أختر الصورة</span>
                                                                    <span class="fileinput-exists"> تغيير </span>
                                                                    <input type="file" name="photo">
                                                                </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput"> إزالة </a>


                                                            </div>
                                                        </div>
                                                        @if ($errors->has('photo'))
                                                            <span class="help-block">
                                                                <strong
                                                                    style="color: red;">{{ $errors->first('photo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> لوجو المزود </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
                                                                <img
                                                                    src="{{asset('/uploads/providers/logos/' . $provider->logo)}}">
                                                            </div>
                                                            <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new"> أختر الصورة</span>
                                                                    <span class="fileinput-exists"> تغيير </span>
                                                                    <input type="file" name="logo">
                                                                </span>
                                                                <a href="javascript:;" class="btn red fileinput-exists"
                                                                   data-dismiss="fileinput"> إزالة </a>


                                                            </div>
                                                        </div>
                                                        @if ($errors->has('logo'))
                                                            <span class="help-block">
                                                                <strong
                                                                    style="color: red;">{{ $errors->first('logo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">وصف المزود </label>
                                                <div class="col-md-9">
                                                    <textarea name="description" class="form-control"
                                                              rows="7">{{$provider->description}}</textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="body-site">
                                                <div class="d-flex">
                                                    <div class="col-m-9">
                                                        <div class="content sections">
                                                            <h4 style="text-align: right"> حدد موقع المزود علي
                                                                الخريطة </h4>
                                                            <input type="text" id="lat" name="latitude"
                                                                   value="{{$provider->latitude}}" required/>
                                                            <input type="text" id="lng" name="longitude"
                                                                   value="{{$provider->longitude}}" required/>
                                                            @if ($errors->has('latitude'))
                                                                <span class="help-block">
                                                                <strong
                                                                    style="color: red;">{{ $errors->first('latitude') }}</strong>
                                                            </span>
                                                            @endif
                                                            <a class="btn btn-info" onclick="getLocation()"> حدد موقعك
                                                                الان </a>
                                                            <div id="map"></div>
                                                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        <script>
            $(document).ready(function() {
            $('select[name="category_id[]"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/admin/get/sub_categories/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#select2Multiple').empty();

                        $('select[name="sub_categories[]"]').append('<option value>المدينة</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');

                        $.each(data, function (index, sub_categories) {
                            console.log(index);
                            $('select[name="sub_categories[]"]').append('<option value="' + sub_categories + '" {{App\Models\ProviderCategory::whereId('sub_categories')->where('provider_id' , $provider->id)->first() != null ? 'selected' : ''}}>' + index + '</option>');

                        });
                    }
                });


            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "أختر الأقسام  للمزود",
                allowClear: true
            });
        });

    </script>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;

            document.getElementById('lat').value = lat; //latitude
            document.getElementById('lng').value = lon; //longitude
            latlon = new google.maps.LatLng(lat, lon)
            mapholder = document.getElementById('mapholder')
            //mapholder.style.height='250px';
            //mapholder.style.width='100%';

            var myOptions = {
                center: latlon, zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL}
            };
            var map = new google.maps.Map(document.getElementById("map"), myOptions);
            var marker = new google.maps.Marker({position: latlon, map: map, title: "You are here!"});
        }

    </script>
    <script type="text/javascript">
        var map;

        function initMap() {


            @if($provider->latitude != null && $provider->longitude != null)
            var latitude = {{$provider->latitude}}; // YOUR LATITUDE VALUE
            var longitude = {{$provider->longitude}};  // YOUR LONGITUDE VALUE
            @else
            var latitude = 24.774265; // YOUR LATITUDE VALUE
            var longitude = 46.738586;  // YOUR LONGITUDE VALUE
            @endif

            console.log(latitude);
            console.log(longitude);
            var myLatLng = {lat: latitude, lng: longitude};

            map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 10,
                gestureHandling: 'true',
                zoomControl: false// disable the default map zoom on double click
            });


            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                //title: 'Hello World'

                // setting latitude & longitude as title of the marker
                // title is shown when you hover over the marker
                title: latitude + ', ' + longitude
            });


            //Listen for any clicks on the map.
            google.maps.event.addListener(map, 'click', function (event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if (marker === false) {
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        markerLocation();
                    });
                } else {
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });


            function markerLocation() {
                //Get location.
                var currentLocation = marker.getPosition();
                //Add lat and lng values to a field that we can save.
                document.getElementById('lat').value = currentLocation.lat(); //latitude
                document.getElementById('lng').value = currentLocation.lng(); //longitude
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap"
            async defer></script>
    <script>
        $(".delete_main_cat").click(function () {
            var id = $(this).attr('id');
            var url = '{{ route("ProviderMainCatRemove", ":id") }}';

            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {
                    if (!result.message) {
                        $(".cat_" + id).fadeOut('1000');
                    }

                }
            });
        });
        $(".delete_sub_cat").click(function () {
            var id = $(this).attr('id');
            var url = '{{ route("ProviderSubCatRemove", ":id") }}';

            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {
                    if (!result.message) {
                        $(".subCat_" + id).fadeOut('1000');
                    }

                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("input[name=delivery]").change(function () {

                if ($("#provider").is(':checked')) {
                    $("#delivery_by").show();
                } else {
                    $("#delivery_by").hide();
                    $("#delivery_price").hide();
                }
            });
            $("input[name=delivery_by]").change(function () {

                if ($("#yes").is(':checked')) {
                    $("#delivery_price").show();
                } else {
                    $("#delivery_price").hide();
                }
            });
        });
    </script>

@endsection
