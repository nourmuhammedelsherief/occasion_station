@extends('admin.layouts.master')

@section('title')
    تعديل السائق
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
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
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/users/2')}}">السائقين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>تعديل سائق</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> السائقين
        <small>اضافة سائق    </small>
    </h1>
@endsection

@section('content')



    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            @include('flash::message')
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">حساب الملف الشخصي</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">المعلومات الشخصية</a>
                                    </li>

                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">تغيير كلمة المرور</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab">اعدادات الخصوصية</a>
                                    </li>
                                </ul>
                            </div>
                            <form role="form" action="/admin/update/user/{{$user->id}}/{{$user->type}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">



                                            <div class="form-group">
                                                <label class="control-label">اسم  المستخدم </label>
                                                <input type="text" name="name" placeholder="اسم  المستخدم" class="form-control" value="{{$user->name}}" />
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">  الأسم  بالكامل </label>
                                                <input type="text" name="full_name" placeholder="الأسم  بالكامل" class="form-control" value="{{$user->full_name}}" />
                                                @if ($errors->has('full_name'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('full_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">رقم الهاتف</label>

                                                <input type="text" name="phone_number" placeholder="رقم الهاتف" class="form-control" value="{{$user->phone_number}}" />
                                                @if ($errors->has('phone_number'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('phone_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label"> البريد الألكتروني</label>

                                                <input type="email" name="email" placeholder="البريد الألكتروني" class="form-control" value="{{$user->email}}" />
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> رقم  الهوية </label>
                                                <input type="number" name="national_id" placeholder="رقم  الهوية" class="form-control" value="{{$user->national_id}}" />
                                                @if ($errors->has('national_id'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('national_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"> الرقم  الوظيفي   </label>
                                                <input type="number" name="job_id" placeholder="الرقم  الوظيفي" class="form-control" value="{{$user->job_id}}" />
                                                @if ($errors->has('job_id'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('job_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">   القطاع   </label>
                                                <input type="text" name="sector" placeholder="القطاع" class="form-control" value="{{$user->sector}}" />
                                                @if ($errors->has('sector'))
                                                    <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('sector') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-body">
                                                <div class="form-group ">
                                                    <label class="control-label col-md-3"> صورة العميل</label>
                                                    <div class="col-md-9">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                                @if($user->photo != null)
                                                                   <img src="{{asset('/uploads/users/'.$user->photo)}}">
                                                                @endif
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
{{--                                            <div class="form-body">--}}
{{--                                                <div class="form-group ">--}}
{{--                                                    <label class="control-label col-md-3"> صورة رخصة القيادة</label>--}}
{{--                                                    <div class="col-md-9">--}}
{{--                                                        <div class="fileinput fileinput-new" data-provides="fileinput">--}}
{{--                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">--}}
{{--                                                                @if($user->driving_licence != null)--}}
{{--                                                                <img src="{{asset('/uploads/driving_licences/'.$user->driving_licence)}}">--}}
{{--                                                             @endif--}}
{{--                                                            </div>--}}
{{--                                                            <div>--}}
{{--                                                            <span class="btn red btn-outline btn-file">--}}
{{--                                                                <span class="fileinput-new"> اختر الصورة </span>--}}
{{--                                                                <span class="fileinput-exists"> تغيير </span>--}}
{{--                                                                <input type="file" name="driving_licence"> </span>--}}
{{--                                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>--}}



{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        @if ($errors->has('driving_licence'))--}}
{{--                                                            <span class="help-block">--}}
{{--                                                               <strong style="color: red;">{{ $errors->first('driving_licence') }}</strong>--}}
{{--                                                            </span>--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}

                                              <br>
                                            <div class="margiv-top-10">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn green">حفظ</button>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->


                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_3">
                                            <form action="/admin/update/pass/{{$user->id}}" method="post">
                                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                                                <div class="form-group">
                                                    <label class="control-label">كلمة المرور الجديدة</label>
                                                    <input type="password" name="password" class="form-control" />
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">إعادة كلمة المرور</label>
                                                    <input type="password" name="password_confirmation" class="form-control" />
                                                    @if ($errors->has('password_confirmation'))
                                                        <span class="help-block">
                                                           <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="margin-top-10">
                                                    <div class="form-actions">
                                                        <button type="submit" class="btn green">حفظ</button>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->
                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="tab_1_4">
                                            <form action="/admin/update/privacy/{{$user->id}}" method="post">
                                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                <table class="table table-light table-hover">

                                                    <tr>
                                                        <td> تفعيل المستخدم</td>
                                                        <td>
                                                            <div class="mt-radio-inline">
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="active" value="1" {{ $user->active == "1" ? 'checked' : '' }}/> نعم
                                                                    <span></span>
                                                                </label>
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="active" value="0" {{$user->active == "0" ? 'checked' : '' }}/> لا
                                                                    <span></span>
                                                                </label>
                                                                @if ($errors->has('active'))
                                                                    <span class="help-block">
                                                                           <strong style="color: red;">{{ $errors->first('active') }}</strong>
                                                                        </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>


                                                </table>
                                                <div class="margin-top-10">
                                                    <div class="form-actions">
                                                        <button type="submit" class="btn green">حفظ</button>

                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>

                                </div>
                                {{-- <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>

                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select[name="address[country]"]').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    url: '/get/cities/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#register_city').empty();



                        $('select[name="address[city]"]').append('<option value>المدينة</option>');
                        // $('select[name="city"]').append('<option value>المدينة</option>');
                        $.each(data['cities'], function(index , cities) {

                            $('select[name="address[city]"]').append('<option value="'+ cities.id +'">'+cities.name+'</option>');

                        });


                    }
                });



            });
        });
    </script>
    <script>
        function getLocation()
        {
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
            else{x.innerHTML="Geolocation is not supported by this browser.";}
        }

        function showPosition(position)
        {
            lat= position.coords.latitude;
            lon= position.coords.longitude;

            document.getElementById('lat').value = lat; //latitude
            document.getElementById('lng').value = lon; //longitude
            latlon=new google.maps.LatLng(lat, lon)
            mapholder=document.getElementById('mapholder')
            //mapholder.style.height='250px';
            //mapholder.style.width='100%';

            var myOptions={
                center:latlon,zoom:14,
                mapTypeId:google.maps.MapTypeId.ROADMAP,
                mapTypeControl:false,
                navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
            };
            var map = new google.maps.Map(document.getElementById("map"),myOptions);
            var marker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
        }

    </script>
    <script type="text/javascript">
        var map;

        function initMap() {

            var latitude = {{$user->latitude}}; // YOUR LATITUDE VALUE
            var longitude =  {{$user->longitude}};  // YOUR LONGITUDE VALUE


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
            google.maps.event.addListener(map, 'click', function(event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if(marker === false){
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function(event){
                        markerLocation();
                    });
                } else{
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });



            function markerLocation(){
                //Get location.
                var currentLocation = marker.getPosition();
                //Add lat and lng values to a field that we can save.
                document.getElementById('lat').value = currentLocation.lat(); //latitude
                document.getElementById('lng').value = currentLocation.lng(); //longitude
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFUMq5htfgLMNYvN4cuHvfGmhe8AwBeKU&callback=initMap" async defer></script>
@endsection
