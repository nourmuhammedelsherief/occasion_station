@extends('provider.layouts.master')

@section('title')
    عرض بيانات  الطلب
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <style>
        #map {
            height: 500px;
            width: 500px;
        }
    </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/provider/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{route('showProviderOrder' , $order->id)}}">عرض بيانات  الطلب</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  عرض بيانات  الطلب</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  عرض بيانات  الطلب
        <small>عرض  عرض بيانات  الطلب</small>
    </h1>
@endsection

@section('content')
    @if (session('msg'))
        <div class="alert alert-danger">
            {{ session('msg') }}
        </div>
    @endif
    @include('flash::message')
    <a href="{{route('MyOrder' , $order->status)}}" class="btn btn-info"> عوده لصفحه الطلبات </a>

    <hr>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered table-responsive">

                <div class="portlet-body">
                    <div class="table-toolbar">
                        <h3 class="text-center"> بيانات  الطلب </h3>
                        <p>  رقم الطلب : <span> {{$order->cart_id}}  </span> </p>
                        <p> سعر الطلب : <span> {{$order->order_price}}  ريال</span> </p>
                        <p>     تفاصيل أضافية : <span> {{$order->cart->more_details}}  </span> </p>
                    </div>
                    <div class="table-toolbar">
                        <h3 class="text-center"> بيانات التوصيل </h3>
                        <p>   تاريخ توصيل الطلب : <span> {{$order->cart->delivery_date}}  </span> </p>
                        <p>   وقت توصيل الطلب : <span> {{$order->cart->delivery_time}}  </span> </p>
                        <p>     عنوان الأستلام : <span> {{$order->cart->delivery_address}}  </span> </p>
                        <p>      الموقع علي الخريطة :
                            @if($order->cart->delivery_latitude != null && $order->cart->delivery_longitude != null)
                                <a type="button"  class="btn btn-info" data-toggle="modal"
                                   data-target="#exampleModalScrollable{{$order->id}}">
                                    عرض الموقع
                                </a>
                        <div class="modal fade" id="exampleModalScrollable{{$order->id}}" tabindex="-1"
                             role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض موقع  أستلام الطلب </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="lat" name="latitude" readonly="yes" required value="{{$order->cart->delivery_latitude}}" />
                                        <input type="text" id="lng" name="longitude" readonly="yes" required value="{{$order->cart->delivery_longitude}}" />
                                        <div id="map"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">أغلاق
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            لم يحدد بعد
                            @endif
                            </p>

                    </div>
                </div>
            </div>
            <h3 class="text-center"> المنتج </h3>
            <div class="portlet light bordered table-responsive">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <h3 class="text-center"> {{$order->product->name}} </h3>
                        <p>      المستخدم :
                            @if($order->user != null)
                                <a type="button"  class="btn btn-info" data-toggle="modal"
                                   data-target="#exampleModalScrollable{{$order->user->id}}">
                                    عرض
                                </a>
                        <div class="modal fade" id="exampleModalScrollable{{$order->user->id}}" tabindex="-1"
                             role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض بيانات  المستخدم </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p> الأسم : {{$order->user->name}} </p>
                                        <p> الجوال : {{$order->user->phone_number}} </p>
                                        <p> المدينه : {{$order->user->city == null ? 'لم تحدد المدينه  بعد' : $order->user->city->name}} </p>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">أغلاق
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            لم يحدد بعد
                            @endif
                            </p>



                                <p>      المنتج :
                                    @if($order->product != null)
                                        <a type="button"  class="btn btn-info" data-toggle="modal"
                                           data-target="#exampleModalScrollable{{$order->product->id}}">
                                            عرض
                                        </a>
                                <div class="modal fade" id="exampleModalScrollable{{$order->product->id}}" tabindex="-1"
                                     role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض بيانات المنتج </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p> أسم المنتج : {{$order->product->name}} </p>
                                                <p>  السعر : {{$order->product->price}} ريال </p>
                                                <p>  أقل كميه للطلب : {{$order->product->less_amount}} </p>
                                                <p>  النشاط :
                                                    @if($order->product->activity == 'sale')
                                                        بيع
                                                    @else
                                                        تأجير
                                                    @endif

                                                </p>
                                                <p>  التوصيل :
                                                    @if($order->product->delivery == 'yes')
                                                        يوجد توصيل
                                                    @else
                                                        لا يوجد توصيل
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">أغلاق
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    لم يحدد بعد
                                    @endif
                                    </p>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/datatable.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('admin/js/table-datatables-managed.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');
            $('body').on('click', '.delete_country', function () {
                var id = $(this).attr('data');

                var swal_text = " {{trans('messages.delete')}}  " + $(this).attr('data_name') + '؟';
                var swal_title = "هل أنت متأكد من الحذف";

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "نعم",
                    cancelButtonText: "أغلاق",
                    closeOnConfirm: false
                }, function () {
                    window.location.href = "{{ url('/') }}" + "/admin/cities/delete/" + id;
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

            var latitude = {{$order->cart->delivery_latitude}}; // YOUR LATITUDE VALUE
            var longitude =  {{$order->cart->delivery_longitude}};  // YOUR LONGITUDE VALUE


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
