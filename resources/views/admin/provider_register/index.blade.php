@extends('admin.layouts.master')

@section('title')
    طلبات تسجيل مزودي الخدمات
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
                <a href="{{url('/admin/provider_registers')}}">طلبات تسجيل مزودي الخدمات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  طلبات تسجيل مزودي الخدمات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض طلبات تسجيل مزودي الخدمات
        <small>عرض طلبات تسجيل مزودي الخدمات</small>
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
                    <h3 class="text-center">
                        @if($status == 'new')
                            جديد
                        @elseif($status == 'completed')
                            مكتمل
                        @elseif($status == 'canceled')
                            ملغي
                        @endif
                    </h3>
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
                            <th> ألأسم</th>
                            <th>اسم المتجر</th>
                            <th> المدينة</th>
                            <th> الأيميل</th>
                            <th> الهاتف</th>
                            <th> الحي</th>
                            <th> الشارع</th>
                            <th> نوع النشاط</th>
                            <th> الموقع</th>
                            <th> العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($providers as $provider)
                            @if($provider->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$provider->name}} </td>
                                    <td> {{$provider->store_name}} </td>
                                    <td> {{$provider->city}} </td>
                                    <td> {{$provider->email}} </td>
                                    <td> {{$provider->phone_number}} </td>
                                    <td> {{$provider->district}} </td>
                                    <td> {{$provider->street}} </td>
                                    <td> {{$provider->activity_type}} </td>
                                    <td>
                                        <a target="_blank" href="{{$provider->url}}">
                                            {{$provider->url}}
                                        </a>
                                    </td>
                                    <td>
                                        @if($status == 'new')
                                            <a class="btn btn-success" href="{{route('complete_provider' , [$provider->id , 'completed'])}}">
                                                <i class="fa fa-user"></i> إكمال
                                            </a>
                                            <a class="btn btn-danger" href="{{route('complete_provider' , [$provider->id , 'canceled'])}}">
                                                <i class="fa fa-trash"></i> إلغاء
                                            </a>
                                        @elseif($status == 'completed')
                                            <a class="btn btn-danger" href="{{route('complete_provider' , [$provider->id , 'canceled'])}}">
                                                <i class="fa fa-trash"></i> إلغاء
                                            </a>
                                        @elseif($status == 'canceled')
                                            <a type="button" class="btn btn-info" data-toggle="modal"
                                               data-target="#exampleModalScrollable{{$provider->id}}">
                                                سبب الإلغاء
                                            </a>
                                            <div class="modal fade" id="exampleModalScrollable{{$provider->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle"> سبب إلغاء المزود </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p> المزود  : {{$provider->name}} </p>
                                                            <p> سبب الإلغاء : {{$provider->cancel_reason}} </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">اغلاق
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    @endif
                        @endforeach
                        </tbody>
                    </table>
                    {{$providers->links()}}
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
                    window.location.href = "{{ url('/') }}" + "/admin/providers/delete/" + id;
                });
            });
        });
    </script>
@endsection
