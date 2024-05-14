@extends('admin.layouts.master')

@section('title')
    مزودي الخدمات
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
                <a href="{{url('/admin/providers')}}">مزودي الخدمات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>  عرض مزودي الخدمات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض  مزودي الخدمات
        <small>عرض  مزودي الخدمات</small>
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

                    @if(auth()->guard('admin')->user()->admin_category_id == 4 or auth()->guard('admin')->user()->admin_category_id == 6)
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <a href="{{route('createProvider')}}">
                                            <button id="sample_editable_1_new"
                                                    class="btn sbold green"> أضافه مزود جديد
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                            <th> الأسم</th>
                            <th> المدينة </th>
                            <!--<th> الأيميل</th>-->
                            {{--                            <th> الهاتف</th>--}}
                            <th> السلايدر </th>
                            <th> النشاط </th>
                            <th>  المنتجات</th>
                            <th> التقييم </th>
                            @if(auth()->guard('admin')->user()->admin_category_id == 4 or auth()->guard('admin')->user()->admin_category_id == 6)
                                <th> مميز </th>
                                <th> vip </th>
                                <th> الأقسام </th>
                                <th> الحالة </th>
                                <th> العمليات</th>
                            @endif
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
                                    <td> {{$provider->city->name}} </td>
                                    {{--                                <!--<td> {{$provider->email}} </td>-->--}}
                                    {{--                                    <td> {{$provider->phone_number}} </td>--}}
                                    <td>
                                        <a class="btn btn-info" href="{{route('providerSlider' , $provider->id)}}">
                                            {{$provider->sliders->count()}}
                                        </a>
                                    </td>
                                    <td>
                                        @if($provider->activity == 'sale')
                                            بيع
                                        @elseif($provider->activity == 'rent')
                                            تأجير
                                        @elseif($provider->activity == 'both')
                                            بيع/تأجير
                                        @endif
                                    </td>

                                    <td> {{$provider->products->count()}} </td>
                                    <td>
                                        <a href="{{route('showProviderRates' , $provider->id)}}" class="btn btn-warning">{{$provider->rates->count()}}</a>
                                    </td>
                                    @if(auth()->guard('admin')->user()->admin_category_id == 4 or auth()->guard('admin')->user()->admin_category_id == 6)
                                        <td>
                                            @if($provider->special == 'true')
                                                <a href="{{route('specialProvider' , [$provider->id , 'false'])}}" class="btn btn-success">نعم</a>
                                            @else
                                                <a href="{{route('specialProvider' , [$provider->id , 'true'])}}" class="btn btn-danger"> لا </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($provider->vip == 'true')
                                                <a href="{{route('vipProvider' , [$provider->id , 'false'])}}" class="btn btn-primary">نعم</a>
                                            @else
                                                <a href="{{route('vipProvider' , [$provider->id , 'true'])}}" class="btn btn-danger"> لا </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{--                                            <a href="{{route('ArrangeProvider' , $provider->id)}}"--}}
                                            {{--                                               class="btn btn-primary">--}}
                                            {{--                                                @if($provider->arrange == null)--}}
                                            {{--                                                    لم يرتب--}}
                                            {{--                                                @else--}}
                                            {{--                                                    {{$provider->arrange}}--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </a>--}}
                                            <a href="{{route('provider_categories' , $provider->id)}}"
                                               class="btn btn-primary">
                                                عرض
                                            </a>
                                        </td>
                                        <td>
                                            @if($provider->stop == 'true')
                                                موقوف
                                            @else
                                                نشط
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-xs green dropdown-toggle" type="button"
                                                        data-toggle="dropdown"
                                                        aria-expanded="false"> العمليات
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-left" role="menu">
                                                    <li>
                                                        <a href="{{route('editProvider' , $provider->id)}}">
                                                            <i class="icon-docs"></i> تعديل </a>
                                                    </li>
                                                    <li>
                                                        <a class="delete_country" data="{{$provider->id}}"
                                                           data_name="{{$provider->name}}">
                                                            <i class="fa fa-key"></i> حذف
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if($provider->stop == 'true')
                                                            <a href="{{route('stopProvider' , [$provider->id , 'false'])}}">
                                                                <i class="fa fa-hand-stop-o"></i>
                                                                إلغاء الإيقاف
                                                            </a>
                                                        @else
                                                            <a href="{{route('stopProvider' , [$provider->id , 'true'])}}">
                                                                <i class="fa fa-stop"></i>
                                                                إيقاف
                                                            </a>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <a href="{{route('orders_completed' , $provider->id)}}">
                                                            <i class="fa fa-eye"></i>
                                                            سجل المبيعات
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
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
