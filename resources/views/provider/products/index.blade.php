@extends('provider.layouts.master')

@section('title')
    منتجاتي
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
                <a href="{{url('/provider/products')}}">منتجاتي</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  منتجاتي</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  منتجاتي
        <small>عرض  منتجاتي</small>
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
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-6">--}}
                        {{--                                <div class="btn-group">--}}
                        {{--                                    <a href="{{route('createMyProduct')}}">--}}
                        {{--                                        <button id="sample_editable_1_new"--}}
                        {{--                                                class="btn sbold green"> أضافه جديد--}}
                        {{--                                            <i class="fa fa-plus"></i>--}}
                        {{--                                        </button>--}}
                        {{--                                    </a>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
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
                            <th> الاسم </th>
                            <th> السعر </th>
                            <th> أقل كميه </th>
                            <th> التوصيل </th>
                            {{--                            <th> العمليات</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($products as $product)
                            @if($product->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$product->name}} </td>
                                    <td> {{$product->price}} </td>
                                    <td> {{$product->less_amount}} </td>
                                    <td>
                                        @if($product->delivery == 'yes')
                                            السعر شامل التوصيل
                                        @else
                                            @if($product->delivery_by == 'provider')
                                                عن طريق المزود
                                            @else
                                                عن طريق التطبيق
                                            @endif
                                        @endif
                                    </td>
                                    {{--                                    <td>--}}
                                    {{--                                        <div class="btn-group">--}}
                                    {{--                                            <button class="btn btn-xs green dropdown-toggle" type="button"--}}
                                    {{--                                                    data-toggle="dropdown"--}}
                                    {{--                                                    aria-expanded="false"> العمليات--}}
                                    {{--                                                <i class="fa fa-angle-down"></i>--}}
                                    {{--                                            </button>--}}
                                    {{--                                            <ul class="dropdown-menu pull-left" role="menu">--}}

                                    {{--                                                <!--<li>-->--}}
                                    {{--                                            <!--    <a href="{{route('editMyProduct' , $product->id)}}">-->--}}
                                    {{--                                                <!--        <i class="icon-docs"></i> تعديل </a>-->--}}
                                    {{--                                                <!--</li>-->--}}
                                    {{--                                                <li>--}}
                                    {{--                                                    <a class="delete_country" data="{{$product->id}}"--}}
                                    {{--                                                       data_name="{{$product->name}}">--}}
                                    {{--                                                        <i class="fa fa-key"></i> حذف--}}
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
                    {{$products->links()}}
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
                    window.location.href = "{{ url('/') }}" + "/provider/products/delete/" + id;
                });
            });
        });
    </script>
@endsection
