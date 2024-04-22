@extends('admin.layouts.master')

@section('title')
    سجل عمليات الطلب
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
                <a href="{{route('showOrder' , $order->id)}}"> تفاصيل الطلب</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  سجل عمليات الطلب</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  سجل عمليات الطلب
        <small>عرض  سجل عمليات الطلب</small>
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
                            <th> الموظف </th>
                            <th> التاريخ </th>
                            <th> العنوان </th>
                            <th> ملاحظه علي الطلب </th>
                            @if(Auth::guard('admin')->user()->role == 'admin' || Auth::guard('admin')->user()->role == 'editor')

                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($notes as $note)
                            @if($note->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>{{$note->admin->name}}</td>
                                    <td> {{$note->created_at->format('Y-m-d')}} </td>
                                    <td>
                                        @if($note->redirection_admin != null)
                                            {{$note->title . $note->redirect->name}}
                                        @else
                                            {{$note->title}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($note->notes != null)
                                            <a type="button"  data-toggle="modal"
                                               data-target="#exampleModalScrollable{{$note->id}}">
                                                {!! str_limit($note->notes , 65) !!}
                                            </a>
                                            <div class="modal fade" id="exampleModalScrollable{{$note->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle"> عرض تفاصيل ملاحظه الموظف علي الطلب </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! $note->notes !!}
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
                    window.location.href = "{{ url('/') }}" + "/admin/cities/delete/" + id;
                });
            });
        });
    </script>
@endsection
