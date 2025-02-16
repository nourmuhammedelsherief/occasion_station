@extends('admin.layouts.master')

@section('title')
    تواصل معنا
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
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/contacts">تواصل معنا</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض تواصل معنا</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض تواصل معنا
        <small>عرض جميع تواصل معنا(المؤرشف)</small>
    </h1>
@endsection

@section('content')
    @include('flash::message')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase"> تواصل معنا</span>
                    </div>

                </div>
                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    <span></span>
                                </label>
                            </th>
                            <th></th>
                            <th> الاسم </th>
                            <th> رقم الجوال </th>
                            <th> الرسالة </th>
                            <th> خيارات </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($contacts as $contact)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{$contact->user->name}} </td>
                                <td>
                                    <a href="tel:{{$contact->user->phone_number}}">
                                        {{$contact->user->phone_number}}
                                    </a>
                                </td>
                                <td >
                                    <a href="{{ url('admin/contacts/show', $contact->id) }}">
                                        {{ \Illuminate\Support\Str::limit($contact->message , 50) }}
                                    </a>
                                </td>

                                {{--                                <td >--}}
                                {{--                                    @if($contact->reply)--}}
                                {{--                                        <a href="{{ url('admin/contacts/show', $contact->id) }}">--}}
                                {{--                                            {{ str_limit($contact->reply , 50) }}--}}
                                {{--                                        </a>--}}
                                {{--                                    @else--}}
                                {{--                                        لم يتم إرسال الرد بعد--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}

                                <td>
                                    <a class="btn btn-primary" href="{{route('archivedContact' , [$contact->id , 'false'])}}">إلغاء الأرشفة</a>
                                    <a class="delete_attribute btn btn-danger" data="{{$contact->id}}" data_name="{{$contact->name}}" >
                                        <i class="fa fa-key"></i> مسح
                                    </a>

                                </td>


                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    {{$contacts->links()}}
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
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_attribute', function() {
                var id = $(this).attr('data');

                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/contacts/delete/"+id;

                });

            });

        });
    </script>



@endsection
