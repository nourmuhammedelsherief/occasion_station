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
            <form method="post" action="{{route('storeProvider')}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه مزودي الخدمات</span>
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
                                                            <option value="{{$city->id}}"> {{$city->name}} </option>
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
                                                <label class="col-md-3 control-label">اسم المزود </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control"
                                                           placeholder="أكتب أسم  المزود" value="{{old('name')}}"
                                                           required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> البريد الألكتروني </label>
                                                <div class="col-md-9">
                                                    <input type="email" name="email" class="form-control"
                                                           placeholder="البريد الألكتروني للمزود"
                                                           value="{{old('email')}}" required>
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
                                                           value="{{old('phone_number')}}" required>
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
                                        <!--               placeholder="أكتب عنوان  المزود" value="{{old('address')}}"-->
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
                                                        <option value="sale"> بيع</option>
                                                        <option value="rent"> تأجير</option>
                                                        <option value="both">كلاهما</option>
                                                    </select>
                                                    @if ($errors->has('activity'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('activity') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> القسم الرئيسي </label>
                                                <div class="col-md-9">
                                                    <select name="category_id" class="form-control" required>
                                                        <option disabled selected> أختر القسم الرئيسي للمزود</option>
                                                        @foreach($categories  as $category)
                                                            <option
                                                                value="{{$category->id}}"> {{$category->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('category_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> حدد نوع الدفع للمزود </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" name="bank_payment" value="true" style="margin: 5px"> تحويل بنكي

                                                    <input type="checkbox" name="online_payment" value="true" style="margin: 5px"> دفع اونلاين

                                                    <input type="checkbox" name="tamara_payment" value="true" style="margin: 5px"> دفع تمارا
                                                </div>
                                            </div>


                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label class="col-md-3 control-label" for="select2Multiple"> الأقسام--}}
                                            {{--                                                    الفرعية </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <select class="select2-multiple form-control"--}}
                                            {{--                                                            name="sub_categories[]" multiple="multiple"--}}
                                            {{--                                                            id="select2Multiple">--}}

                                            {{--                                                    </select>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صوره المزود </label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail"
                                                                 data-trigger="fileinput"
                                                                 style="width: 200px; height: 150px;">
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
                                                              rows="7"></textarea>
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
                                                            <input type="text" id="lat" name="latitude" required/>
                                                            <input type="text" id="lng" name="longitude" required/>
                                                            <a class="btn btn-info" onclick="getLocation()"> حدد موقعك
                                                                الان </a>
                                                            @if ($errors->has('latitude'))
                                                                <span class="help-block">
                                                                <strong
                                                                    style="color: red;">{{ $errors->first('latitude') }}</strong>
                                                            </span>
                                                            @endif
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
        $(document).ready(function () {
            $('select[name="category_id[]"]').on('change', function () {
                var id = $(this).val();
                $.ajax({
                    url: '/admin/get/sub_categories/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#select2Multiple').empty();

                        $('select[name="sub_categories[]"]').append('<option value>القسم الفرعي</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');

                        $.each(data, function (index, sub_categories) {
                            console.log(index);
                            $('select[name="sub_categories[]"]').append('<option value="' + sub_categories + '">' + index + '</option>');

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

            var latitude = 24.774265; // YOUR LATITUDE VALUE
            var longitude = 46.738586;  // YOUR LONGITUDE VALUE


            var myLatLng = {lat: latitude, lng: longitude};

            map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 5,
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
@endsection
