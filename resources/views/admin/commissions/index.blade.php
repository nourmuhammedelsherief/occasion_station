@extends('admin.layouts.master')

@section('title')
    العمولات الخاصة بالمزود {{$provider->name}}
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
                <a href="{{url('/provider/commissions')}}">العمولات الخاصة بالمزود {{$provider->name}}</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض  العمولات الخاصة بالمزود {{$provider->name}}</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض العمولات الخاصة بالمزود {{$provider->name}}
        <small>عرض العمولات الخاصة بالمزود {{$provider->name}}</small>
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
                                <h3> اجمالي العمولات المستحقه علي المزود هي <span> : {{$provider->commission}} </span> </h3>
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
                            <th> تاريخ الدفع </th>
                            <th> المبلغ </th>
                            <th> صوره التحويل </th>
                            <th> حاله التاكيد </th>
                            <th> العمليات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0 ?>
                        @foreach($commissions as $commission)
                            @if($commission->parent_id == null)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$commission->created_at->format('d-m-Y')}} </td>
                                    <td> {{$commission->amount}} </td>
                                    <td>
                                        @if($commission->transfer_photo != null)
                                            <a type="button"  data-toggle="modal"
                                               data-target="#exampleModalScrollable{{$commission->id}}">
                                                <img class="imageresource" src="{{asset('/uploads/transfers/'.$commission->transfer_photo)}}" height="100" width="180">
                                            </a>
                                            <div class="modal fade" id="exampleModalScrollable{{$commission->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle">صورة التحويل البنكي للعمولة</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <img src="{{asset('/uploads/transfers/'.$commission->transfer_photo)}}"
                                                                 width="500px"/>
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
                                        @if($commission->status == 'wait')
                                            بأنتظار التأكيد
                                        @elseif($commission->status == 'done')
                                            عمليه مؤكده
                                        @elseif($commission->status == 'canceled')
                                            عمليه ملغية
                                        @endif
                                    </td>
                                    <td>
{{--                                        <a class="btn btn-info" href="{{route('ConfirmCommission' , $commission->id)}}"> تأكيد  </a>--}}
{{--                                        <a class="btn btn-danger" href="{{route('CancelCommission' , $commission->id)}}"> الغاء  </a>--}}
                                        @if($commission->status == 'wait')
                                            <a class="btn btn-info" href="{{route('ConfirmCommission' , $commission->id)}}"> تأكيد  </a>
                                        @endif
                                        @if($commission->status != 'canceled')
                                            <a class="btn btn-danger" href="{{route('CancelCommission' , $commission->id)}}"> الغاء  </a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    {{--                    {{$commissions->links()}}--}}
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
                    window.location.href = "{{ url('/') }}" + "/provider/commissions/delete/" + id;
                });
            });
        });
    </script>
@endsection
