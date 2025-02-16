@extends('provider.layouts.master')

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
                <a href="{{url('/provider/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/provider/orders/' . $status)}}">الطلبات</a>
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
            @elseif($status == 'new_no_paid')
                الجديده بأنتظار تأكيد الدفع
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
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                                    <span></span>
                                </label>
                            </th>
                            <th></th>
                            <th> رقم الطلب</th>
                            <th> المستخدم</th>
                            <th> سعر الطلب</th>
                            <th> تاريخ  المناسبة </th>
                            <th> حاله الطلب</th>
                            <th> تفاصيل الطلب</th>
                            {{--                            <th> العمليات</th>--}}
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
                                    <td> {{$order->user->name}} </td>
                                    <td> {{$order->total_price}} </td>
                                    <td> {{$order->delivery_date}} </td>

                                    <td>
                                        @if($order->status == 'new_paid')
                                            جديد مدفوع
                                        @elseif($order->status == 'new_no_paid')
                                            جديد بأنتظار تأكيد الدفع
                                        @elseif($order->status == 'works_on')
                                            جاري العمل عليه
                                        @elseif($order->status == 'completed')
                                            مكتمل
                                        @elseif($order->status == 'canceled')
                                            ملغي
                                        @endif

                                    </td>

                                    <td>
                                        <a href="{{route('showProviderOrder' , $order->id)}}" class="btn btn-info"> عرض
                                            الطلب </a>
                                    </td>
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
