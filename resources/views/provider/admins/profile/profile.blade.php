@extends('provider.layouts.master')

@section('title')
    @lang('messages.my_profile')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <style>
        #map {
            height: 500px;
            width: 800px;
        }
    </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ url('provider/home') }}">@lang('messages.control_panel')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('/provider/profile') }}">@lang('messages.my_profile')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('messages.show')   @lang('messages.my_profile')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">@lang('messages.show')  @lang('messages.my_profile')
        <small>@lang('messages.edit')  @lang('messages.my_profile')</small>
    </h1>
@endsection

@section('content')

    @if(session()->has('msg'))

        <p class="alert alert-success" style="width: 100%">

            {{ session()->get('msg') }}

        </p>
    @endif

    <form class="form-horizontal" method="post" action="{{ url('/provider/profileEdit') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-lg-8">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> @lang('messages.main_data')</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="btn-group"></div>
                        <div class="form-group">
                            <label for="username" class="col-lg-3 control-label">@lang('messages.name')</label>
                            <div class="col-lg-9">
                                <input id="username" name="name" type="text" value="{{ $data->name }}" class="form-control" placeholder="@lang('messages.name')">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-lg-3 control-label">@lang('messages.email')</label>
                            <div class="col-lg-9">
                                <input id="email" name="email" type="email" value="{{ $data->email }}" class="form-control" placeholder="@lang('messages.email')">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="col-lg-3 control-label">@lang('messages.phone_number')</label>
                            <div class="col-lg-9">
                                <input id="phone" name="phone_number" type="text" value="{{ $data->phone_number }}" class="form-control" placeholder="@lang('messages.phone_number')">
                                @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">هل السعر شامل التوصيل ؟</label>
                            <div class="col-md-9">
                                <input type="radio" name="delivery" {{$data->delivery == 'false' ? 'checked' : ''}}  value="false"> نعم
                                <input type="radio" name="delivery" {{$data->delivery == 'true' ? 'checked' : ''}} id="provider" value="true"> لا
                                @if ($errors->has('delivery'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('delivery') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" id="delivery_by" style="{{$data->delivery == 'false' ? 'display: none' : 'display: block'}}">
                            <label class="col-md-3 control-label">من سيقوم بالتوصيل</label>
                            <div class="col-md-9">
                                <input type="radio" name="delivery_by" {{$data->delivery_by == 'provider' ? 'checked' : ''}} value="provider">  المزود من سيقوم بالتوصيل
                                <input type="radio" name="delivery_by" {{$data->delivery_by == 'app' ? 'checked' : ''}} id="yes" value="app"> التوصيل عن طريق التطبيق
                                @if ($errors->has('delivery_by'))
                                    <span class="help-block">
                                        <strong style="color: red;">{{ $errors->first('delivery_by') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div id="delivery_price" style="{{$data->delivery_by == 'app' ? 'display: none' : 'display: block'}}">
                            <div class="form-group">
                                <label class="col-md-3 control-label">أستلام المنتج من المتجر</label>
                                <div class="col-md-9">
                                    <input type="radio" name="store_receiving" {{$data->store_receiving == 'true' ? 'checked' : ''}} value="true"> نعم
                                    <input type="radio" name="store_receiving" {{$data->store_receiving == 'false' ? 'checked' : ''}} value="false"> لا
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
                                    <input type="number" name="delivery_price" class="form-control" placeholder="ادخل سعر التوصيل للمزود" value="{{$data->delivery_price}}">
                                    @if ($errors->has('delivery_price'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('delivery_price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="body-site">
                            <div class="d-flex">
                                <div class="col-m-9">
                                    <div class="content sections">
                                        <h4 style="text-align: right"> حدد موقعك علي
                                            الخريطة </h4>
                                        <input type="text" id="lat" name="latitude"
                                               value="{{$data->latitude}}" required/>
                                        <input type="text" id="lng" name="longitude"
                                               value="{{$data->longitude}}" required/>
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

                        <div style="clear: both"></div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-lg-2 col-lg-offset-10">
                                    {{--<button type="submit" class="btn green btn-block">حفظ</button>--}}
                                    <input class="btn green btn-block" type="submit" value="@lang('messages.save')" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{--{!! Form::close() !!}--}}
@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
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


            @if($data->latitude != null && $data->longitude != null)
            var latitude = {{$data->latitude}}; // YOUR LATITUDE VALUE
            var longitude = {{$data->longitude}};  // YOUR LONGITUDE VALUE
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
                    $("#delivery_price").hide();
                } else {
                    $("#delivery_price").show();
                }
            });
        });
    </script>

@endsection

