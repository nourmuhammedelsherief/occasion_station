@extends('admin.layouts.master')

@section('title')
    @lang('messages.product_modifiers')
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
                <a href="{{url('/admin/product_modifiers' , $product->id)}}">@lang('messages.product_modifiers')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  @lang('messages.product_modifiers')</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  @lang('messages.product_modifiers')
        <small>عرض  @lang('messages.product_modifiers') ({{app()->getLocale() == 'ar' ? $product->name : $product->name_en}})</small>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="{{route('createProductModifier' , $product->id)}}">
                                        <button id="sample_editable_1_new"
                                                class="btn sbold green"> أضافه جديد
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="{{route('ProductOption' , $product->id)}}">
                                        <button id="sample_editable_1_new"
                                                class="btn sbold green"> الإضافات
                                            <i class="fa fa-arrow-left"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                            <th> الوصف </th>
                            <th> عدد الإضافات المطلوب </th>
                            <th> العمليات</th>
                            @if(Auth::guard('admin')->user()->role == 'admin' || Auth::guard('admin')->user()->role == 'editor')

                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($modifiers as $modifier)
                            @if($modifier->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{app()->getLocale() == 'ar' ? $modifier->name_ar : $modifier->name_en}} </td>
                                    <td>
                                        {!! app()->getLocale() == 'ar' ? $modifier->details_ar : $modifier->details_en !!}
                                    </td>
                                    <td>
                                        {{$modifier->count}}
                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{route('editProductModifier' , $modifier->id)}}">
                                            <i class="fa fa-edit"></i>
                                            تعديل
                                        </a>
                                        <a class="delete_country btn btn-danger" data="{{$modifier->id}}"
                                           data_name="{{app()->getLocale() == 'ar' ? $modifier->name_ar : $modifier->name_en}}">
                                            <i class="fa fa-key"></i> حذف
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
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
                    window.location.href = "{{ url('/') }}" + "/admin/product_modifiers/delete/" + id;
                });
            });
        });
    </script>
@endsection
