@extends('provider.layouts.master')

@section('title')
    عرض بيانات الطلب
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
                @if(auth()->guard('admin')->user()->admin_category_id == 4)
                    <a href="{{route('showOrder' , $order->id)}}">عرض بيانات الطلب</a>
                @elseif(auth()->guard('admin')->user()->admin_category_id != 4)
                    <a href="{{route('showOrderE' , $order->id)}}">عرض بيانات الطلب</a>
                @endif
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  عرض بيانات  الطلب</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض عرض بيانات الطلب
        <small>عرض عرض بيانات الطلب</small>
    </h1>
@endsection

@section('content')
    @if (session('msg'))
        <div class="alert alert-danger">
            {{ session('msg') }}
        </div>
    @endif
    @include('flash::message')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered table-responsive">

                <div class="portlet-body">
                    <div class="table-toolbar">
                        <h3 class="text-center"> بيانات الطلب </h3>
                        <p> رقم الطلب : <span> {{$order->id}}  </span></p>
                        <p> تاريخ الطلب : <span> {{$order->created_at->format('Y-m-d H:i:s')}}  </span></p>
                        <p> سعر المنتجات : <span> {{$order->items_price}}  ريال</span></p>
                        @if($order->delivery_price > 0)
                            <p>سعر التوصيل : <span> {{$order->delivery_price}}  ريال</span></p>
                        @endif
                        @if($order->store_receiving == 'true')
                            <p> سيقوم العميل باستلام المنتج من المزود </p>
                        @endif
                        @if($order->tax_value > 0)
                            <p> قيمة الضريبة : <span> {{$order->tax_value}}  ريال</span></p>
                        @endif
                        <p> السعر ألإجمالي : <span> {{$order->total_price}}  ريال</span></p>
                        <p> تفاصيل أضافية : <span> {{$order->more_details}}  </span></p>
                        @if($order->tamara_payment == 'true')
                            <p> الدفع بواسطه : <span> تمارا  </span></p>
                            <p>
                                نوع الدفع :
                                @if($order->tamara_instalment == 0)
                                    دفعه واحده
                                @elseif($order->tamara_instalment == 2)
                                    علي دفعتين
                                @elseif($order->tamara_instalment == 3)
                                    علي ثلاث دفعات
                                @elseif($order->tamara_instalment == 4)
                                    علي اربع دفعات
                                @endif
                            </p>
                            <p> رقم الطلب في تمارا هو : <span> {{$order->tamara_order_id}}  </span></p>
                        @endif
                    </div>
                    {{--                    <div class="table-toolbar">--}}
                    {{--                        <h3 class="text-center"> بيانات الدفع </h3>--}}
                    {{--                        @if($order->tamara_payment == 'true')--}}
                    {{--                            <p> الدفع بواسطه : <span> تمارا  </span></p>--}}
                    {{--                            <p>--}}
                    {{--                                نوع الدفع :--}}
                    {{--                                @if($order->tamara_instalment == 0)--}}
                    {{--                                    دفعه واحده--}}
                    {{--                                @elseif($order->tamara_instalment == 2)--}}
                    {{--                                    علي دفعتين--}}
                    {{--                                @elseif($order->tamara_instalment == 3)--}}
                    {{--                                    علي ثلاث دفعات--}}
                    {{--                                @elseif($order->tamara_instalment == 4)--}}
                    {{--                                    علي اربع دفعات--}}
                    {{--                                @endif--}}
                    {{--                            </p>--}}
                    {{--                            <p> رقم الطلب في تمارا هو  : <span> {{$order->tamara_order_id}}  </span></p>--}}
                    {{--                        @elseif($order->tamara_payment == 'true' and $order->payment_type == 'bank_transfer')--}}
                    {{--                            <p> نوع الدفع : <span> تحويل بنكي </span></p>--}}
                    {{--                            <p> صورة التحويل البنكي : <span>--}}
                    {{--                                    @if($order->transfer_photo != null)--}}
                    {{--                                        <a type="button" class="btn btn-success" data-toggle="modal"--}}
                    {{--                                           data-target="#exampleModalScrollableUser{{$order->id}}">--}}
                    {{--                                                عرض--}}
                    {{--                                               <i class="fa fa-eye"></i>--}}
                    {{--                                            </a>--}}
                    {{--                                           <div class="modal fade" id="exampleModalScrollableUser{{$order->id}}" tabindex="-1"--}}
                    {{--                                             role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">--}}
                    {{--                                            <div class="modal-dialog modal-dialog-scrollable" role="document">--}}
                    {{--                                                <div class="modal-content">--}}
                    {{--                                                    <div class="modal-header">--}}
                    {{--                                                        <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض صوه التحويل البنكي للطلب</h5>--}}
                    {{--                                                        <button type="button" class="close" data-dismiss="modal"--}}
                    {{--                                                                aria-label="Close">--}}
                    {{--                                                            <span aria-hidden="true">&times;</span>--}}
                    {{--                                                        </button>--}}
                    {{--                                                    </div>--}}
                    {{--                                                    <div class="modal-body">--}}
                    {{--                                                        <img src="{{asset('/uploads/transfers/' . $order->transfer_photo)}}">--}}
                    {{--                                                    </div>--}}
                    {{--                                                    <div class="modal-footer">--}}
                    {{--                                                        <button type="button" class="btn btn-secondary"--}}
                    {{--                                                                data-dismiss="modal">أغلاق--}}
                    {{--                                                        </button>--}}
                    {{--            --}}
                    {{--                                                    </div>--}}
                    {{--                                                </div>--}}
                    {{--                                            </div>--}}
                    {{--                                        </div>--}}
                    {{--                                    @endif--}}
                    {{--                                </span>--}}
                    {{--                            </p>--}}
                    {{--                        @elseif($order->tamara_payment == 'true' and $order->payment_type == 'online')--}}
                    {{--                        @endif--}}
                    {{--                        <p> تاريخ الطلب : <span> {{$order->created_at->format('Y-m-d H:i:s')}}  </span></p>--}}
                    {{--                        <p> سعر المنتجات : <span> {{$order->items_price}}  ريال</span></p>--}}
                    {{--                        @if($order->delivery_price > 0)--}}
                    {{--                            <p>سعر التوصيل : <span> {{$order->delivery_price}}  ريال</span></p>--}}
                    {{--                        @endif--}}
                    {{--                        @if($order->tax_value > 0)--}}
                    {{--                            <p> قيمة الضريبة : <span> {{$order->tax_value}}  ريال</span></p>--}}
                    {{--                        @endif--}}
                    {{--                        <p> السعر ألإجمالي : <span> {{$order->total_price}}  ريال</span></p>--}}
                    {{--                        <p> تفاصيل أضافية : <span> {{$order->more_details}}  </span></p>--}}

                    {{--                    </div>--}}
                    <div class="table-toolbar">
                        <h3 class="text-center"> بيانات العميل </h3>
                        <p> اسم العميل : <span> {{$order->user->name}}  </span></p>
                        <p> رقم جوال العميل : <span> {{$order->user->phone_number}}  </span></p>
                        <p> تاريخ توصيل الطلب : <span> {{$order->delivery_date}}  </span></p>
                        <p> وقت توصيل الطلب : <span> {{$order->delivery_time}}  </span></p>
                        <p> عنوان استلام العميل : <span> {{$order->delivery_address}}  </span></p>
                        <p> موقع العميل علي الخريطة :
                        @if($order->delivery_latitude != null && $order->delivery_longitude != null)
                            {{--                                <a type="button" class="btn btn-info" data-toggle="modal"--}}
                            {{--                                   data-target="#exampleModalScrollable{{$order->id}}">--}}
                            {{--                                    عرض الموقع--}}
                            {{--                                </a>--}}
                            <?php $location = 'https://www.google.com/maps?q=' . $order->delivery_latitude . ',' . $order->delivery_longitude; ?>
                            <p><a href="{{$location}}" target="_blank"> {{$location}} </a></p>
                            <div class="modal fade" id="exampleModalScrollable{{$order->id}}" tabindex="-1"
                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض موقع أستلام
                                                الطلب </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" id="lat" name="latitude" readonly="yes" required
                                                   value="{{$order->delivery_latitude}}"/>
                                            <input type="text" id="lng" name="longitude" readonly="yes" required
                                                   value="{{$order->delivery_longitude}}"/>
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
            <h3 class="text-center"> المنتجات </h3>
            @if($order->orders->count() > 0)
                @foreach($order->orders as $item)
                    <div class="portlet light bordered table-responsive">
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <h3 class="text-center"> {{$item->product->name}} </h3>
                                {{--                                <p> المستخدم :--}}
                                {{--                                    @if($item->user != null)--}}
                                {{--                                        <a type="button" class="btn btn-info" data-toggle="modal"--}}
                                {{--                                           data-target="#exampleModalScrollable{{$item->user->id}}">--}}
                                {{--                                            عرض--}}
                                {{--                                        </a>--}}
                                {{--                                <div class="modal fade" id="exampleModalScrollable{{$item->user->id}}" tabindex="-1"--}}
                                {{--                                     role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">--}}
                                {{--                                    <div class="modal-dialog modal-dialog-scrollable" role="document">--}}
                                {{--                                        <div class="modal-content">--}}
                                {{--                                            <div class="modal-header">--}}
                                {{--                                                <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض بيانات--}}
                                {{--                                                    المستخدم </h5>--}}
                                {{--                                                <button type="button" class="close" data-dismiss="modal"--}}
                                {{--                                                        aria-label="Close">--}}
                                {{--                                                    <span aria-hidden="true">&times;</span>--}}
                                {{--                                                </button>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="modal-body">--}}
                                {{--                                                <p> الأسم : {{$item->user->name}} </p>--}}
                                {{--                                                <p> الجوال : {{$item->user->phone_number}} </p>--}}
                                {{--                                                <p> المدينه--}}
                                {{--                                                    : {{$item->user->city == null ? 'لم تحدد المدينه  بعد' : $item->user->city->name}} </p>--}}

                                {{--                                            </div>--}}
                                {{--                                            <div class="modal-footer">--}}
                                {{--                                                <button type="button" class="btn btn-secondary"--}}
                                {{--                                                        data-dismiss="modal">أغلاق--}}
                                {{--                                                </button>--}}

                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                @else--}}
                                {{--                                    لم يحدد بعد--}}
                                {{--                                    @endif--}}
                                {{--                                    </p>--}}


                                    <p> المنتج :
                                        @if($item->product != null)
                                            <a type="button" class="btn btn-info" data-toggle="modal"
                                               data-target="#exampleModalScrollable{{$item->product->id}}">
                                                عرض
                                            </a>
                                    <div class="modal fade" id="exampleModalScrollable{{$item->product->id}}"
                                         tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalScrollableTitle"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض
                                                        بيانات المنتج </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p> أسم المنتج : {{$item->product->name}} </p>
                                                    <p> السعر : {{$item->product->price}} ريال </p>
                                                    {{--                                                        <p> أقل كميه للطلب : {{$item->product->less_amount}} </p>--}}
                                                    <p> الكميه في الطلب : {{$item->product_count}} </p>
                                                    <p> النشاط :
                                                        @if($item->product->activity == 'sale')
                                                            بيع
                                                        @elseif($item->product->activity == 'rent')
                                                            تأجير
                                                        @else
                                                            بيع / تأجير
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
                @endforeach
            @else
                لا يوجد منتجات في هذا الطلب
            @endif
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

            var latitude = {{$order->delivery_latitude}}; // YOUR LATITUDE VALUE
            var longitude = {{$order->delivery_longitude}};  // YOUR LONGITUDE VALUE


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
