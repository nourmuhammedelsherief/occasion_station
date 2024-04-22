@extends('admin.layouts.master')

@section('title')
    الطلبات
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/orders/' . $status)}}">الطلبات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  الطلبات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> عرض الطلبات
        @if($status != null)
            @if($status == 'new_paid')
                الجديده المدفوعه
            @elseif($status == 'works_on')
                الجاري العمل عليها
            @elseif($status == 'completed')
                المكتمله
            @elseif($status == 'canceled')
                الملغية
            @endif
        @endif
        <small>عرض الطلبات</small>
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
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                           id="sample_1">
                        <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1.checkboxes"/>
                                    <span></span>
                                </label>
                            </th>
                            <th></th>
                            <th> رقم الطلب</th>
                            <th> تاريخ الطلب </th>
                            <th> العميل</th>
                            <th>المزودين</th>
                            <th> السعر </th>
                            <th> تاريخ  المناسبة </th>
                            <th> الموظف </th>
                            <th> حاله الطلب</th>
                            <th> تفاصيل الطلب</th>
                            {{--                            <th> العمليات</th>--}}
                            @if(Auth::guard('admin')->user()->admin_category_id == 4)
                                <th> السجل </th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($orders as $order)
                            @if($order->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$order->id}} </td>
                                    <td> {{$order->created_at->format('Y-m-d H:i:s')}} </td>
                                    <td>
                                        @if($order->user)
                                            <a type="button" class="btn btn-info" data-toggle="modal"
                                               data-target="#exampleModalScrollableUser{{$order->id}}">
                                                عرض
                                            </a>
                                            <div class="modal fade" id="exampleModalScrollableUser{{$order->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض بيانات
                                                                المستخدم </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p> الأسم : {{$order->user->name}} </p>
                                                            <p> الجوال : {{$order->user->phone_number}} </p>
                                                            <p> المدينه
                                                                : {{$order->user->city == null ? 'لم تحدد المدينه  بعد' : $order->user->city->name}} </p>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">أغلاق
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->orders and $order->orders->count() > 0)
                                            <a type="button" class="btn btn-info" data-toggle="modal"
                                               data-target="#exampleModalScrollableProvider{{$order->id}}">
                                                عرض
                                            </a>
                                            <div class="modal fade" id="exampleModalScrollableProvider{{$order->id}}"
                                                 tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض بيانات
                                                                مزودي الخدمات </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @foreach($order->orders->unique('provider_id') as $item)
                                                                <p> الأسم : {{$item->provider->name}} </p>
                                                                <p> الجوال : {{$item->provider->phone_number}} </p>
                                                                <p> الأيميل : {{$item->provider->email}} </p>
                                                                <p> المدينه : {{$item->provider->city->name}} </p>
                                                                <hr>
                                                            @endforeach
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">أغلاق
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($order->provider)
                                            {{$order->provider->name}}
                                        @endif
                                    </td>
                                    <td> {{$order->total_price == null ? $order->order_price : $order->total_price}} </td>
                                    <td> {{$order->delivery_date}} </td>
                                    <td> {{\App\Models\AdminOrder::whereOrderId($order->id)->first() == null ? (\App\Models\AdminOrder::whereOrderId($order->cart_id)->first() == null ? 'لم يحدد' : App\Models\AdminOrder::whereOrderId($order->cart_id)->first()->admin->name) : App\Models\AdminOrder::whereOrderId($order->id)->first()->admin->name}} </td>
                                    <td>
                                        @if($order->status == 'new_paid')
                                            جديد مدفوع
                                        @elseif($order->status == 'works_on')
                                            جاري العمل عليه
                                        @elseif($order->status == 'completed')
                                            مكتمل
                                        @elseif($order->status == 'canceled')
                                            ملغي
                                        @endif

                                    </td>

                                    <td>
                                        @if(auth()->guard('admin')->user()->admin_category_id == 4)
                                            <a href="{{route('showOrder' , $order->id)}}" class="btn btn-info"> عرض
                                            </a>
                                        @elseif(auth()->guard('admin')->user()->admin_category_id != 4 and auth()->guard('admin')->user()->admin_category_id == 3)
                                            <a href="{{route('showOrderE' , $order->cart_id)}}" class="btn btn-info"> عرض
                                            </a>
                                        @elseif(auth()->guard('admin')->user()->admin_category_id != 4 and auth()->guard('admin')->user()->admin_category_id == 5)
                                            <a href="{{route('showOrderE' , $order->id)}}" class="btn btn-info"> عرض
                                            </a>
                                        @endif

                                    </td>
                                    @if(Auth::guard('admin')->user()->admin_category_id == 4)
                                        <td>
                                            <a class="btn btn-primary" href="{{route('orderHistory' , $order->id)}}">  عرض </a>
                                        </td>
                                    @endif
                                    {{--                                    <td>--}}
                                    {{--                                        <div class="btn-group">--}}
                                    {{--                                            <button class="btn btn-xs green dropdown-toggle" type="button"--}}
                                    {{--                                                    data-toggle="dropdown"--}}
                                    {{--                                                    aria-expanded="false"> العمليات--}}
                                    {{--                                                <i class="fa fa-angle-down"></i>--}}
                                    {{--                                            </button>--}}
                                    {{--                                            <ul class="dropdown-menu pull-left" role="menu">--}}
                                    {{--                                                <li>--}}
                                    {{--                                                    <a href="{{route('PaymentDone' , $order->id)}}">--}}
                                    {{--                                                        <i class="icon-docs"></i>--}}
                                    {{--                                                        تأكيد الدفع--}}
                                    {{--                                                    </a>--}}
                                    {{--                                                    <a href="{{route('CancelOrder' , $order->id)}}">--}}
                                    {{--                                                        <i class="icon-docs"></i>--}}
                                    {{--                                                        الغاء الطلب--}}
                                    {{--                                                    </a>--}}
                                    {{--                                                </li>--}}
                                    {{--                                            </ul>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </td>--}}
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    {{$orders->links()}}
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
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
@endsection
