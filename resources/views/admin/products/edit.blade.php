@extends('admin.layouts.master')

@section('title')
    المنتجات
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection


@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}"> لوحه التحكم </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/products')}}">المنتجات</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض  المنتجات</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض المنتجات
        <small> المنتجات</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateProduct' , $product->id)}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> تعديل المنتجات</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered table-responsive">
                                <div class="portlet-body form">
                                    <div class="form-horizontal" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> مزود الخدمة </label>
                                                <div class="col-md-9">
                                                    <select name="provider_id" class="form-control" required>
                                                        <option disabled selected> أختر المزود</option>
                                                        @foreach($providers as $provider)
                                                            <option
                                                                value="{{$provider->id}}" {{$product->provider_id == $provider->id ? 'selected' : ''}}> {{$provider->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('provider_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('provider_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">قسم مزود الخدمة </label>
                                                <div class="col-md-9">
                                                    <select name="category_id" class="form-control" required>
                                                        <option disabled selected> أختر القسم للمزود</option>
                                                        @foreach($categories as $category)
                                                            <option
                                                                value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}> {{$category->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('category_id'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">أسم المنتج بالعربي</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control"
                                                           placeholder="أكتب أسم  المنتج باللغة العربية" value="{{$product->name}}"
                                                           required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">أسم المنتج بالانجليزي</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name_en" class="form-control"
                                                           placeholder="أكتب أسم  المنتج باللغة الانجليزية" value="{{$product->name_en}}"
                                                           required>
                                                    @if ($errors->has('name_en'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('name_en') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">سعر المنتج قبل الخصم </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="price_before_discount"
                                                           class="form-control"
                                                           placeholder="أضف سعر المنتج قبل الخصم"
                                                           value="{{$product->price_before_discount}}"
                                                           required>
                                                    @if ($errors->has('price_before_discount'))
                                                        <span class="help-block">
                                               <strong
                                                   style="color: red;">{{ $errors->first('price_before_discount') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">سعر المنتج </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="price" class="form-control"
                                                           placeholder="أكتب سعر المنتج" value="{{$product->price}}"
                                                           required>
                                                    @if ($errors->has('price'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('price') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أقل كميه للطلب من المنتج </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="less_amount" class="form-control"
                                                           placeholder="أدخل أقل كميه للطلب من  المنتج"
                                                           value="{{$product->less_amount}}"
                                                           required>
                                                    @if ($errors->has('less_amount'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('less_amount') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> النشاط </label>
                                                <div class="col-md-9">
                                                    <select name="activity" id="providerActivity" class="form-control" required>
                                                        <option disabled selected> أختر النشاط للمزود</option>
                                                        @if($product->activity == 'sale')
                                                            <option
                                                                value="sale" {{$product->activity == 'sale' ? 'selected' : ''}}>
                                                                بيع
                                                            </option>
                                                        @elseif($product->activity == 'rent')
                                                            <option
                                                                value="rent" {{$product->activity == 'rent' ? 'selected' : ''}}>
                                                                تأجير
                                                            </option>
                                                        @else
                                                            <option
                                                                value="sale" {{$product->activity == 'sale' ? 'selected' : ''}}>
                                                                بيع
                                                            </option>
                                                            <option
                                                                value="rent" {{$product->activity == 'rent' ? 'selected' : ''}}>
                                                                تأجير
                                                            </option>
                                                        @endif
                                                    </select>
                                                    @if ($errors->has('activity'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('activity') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> تفاصيل المنتج </label>
                                                <div class="col-md-9">
                                                    <textarea name="description" rows="5" class="form-control">{{$product->description}}</textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('description') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أحتياجات المنتج </label>
                                                <div class="col-md-9">
                                                    <textarea name="product_requirements" rows="5"
                                                              class="form-control">{{$product->product_requirements}}</textarea>
                                                    @if ($errors->has('product_requirements'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('product_requirements') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--                                            <div class="form-group" id="delivery_price" style="display: {{$product->delivery_by == 'app' ? 'block' : 'none'}}">--}}
                                            {{--                                                <label class="col-md-3 control-label">سعر التوصيل </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <input type="number" name="delivery_price" class="form-control"--}}
                                            {{--                                                           placeholder="أكتب سعر التوصيل"--}}
                                            {{--                                                           value="{{$product->delivery_price}}"--}}
                                            {{--                                                           required>--}}
                                            {{--                                                    @if ($errors->has('delivery_price'))--}}
                                            {{--                                                        <span class="help-block">--}}
                                            {{--                                                            <strong--}}
                                            {{--                                                                style="color: red;">{{ $errors->first('delivery_price') }}</strong>--}}
                                            {{--                                                        </span>--}}
                                            {{--                                                    @endif--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label col-md-3">
                                                        صور المنتج
                                                    </label>
                                                    @foreach($images as $image)
                                                        <div
                                                            class="col-md-3 test text-center col-sm-4 img_{{ $image->id }}">
                                                            <p>
                                                                <img
                                                                    src="{{ URL::to('uploads/products/'.$image->photo) }}"
                                                                    class="img-fluid" height="150" id="file_name"></p>
                                                            <a id="{{ $image->id }}"
                                                               style="color: white;text-decoration: none;"
                                                               class="delete_image hideDiv btn btn-danger">
                                                                <i class="glyphicon glyphicon-trash "></i> مسح</a>
                                                        </div>
                                                    @endforeach
                                                </div>
{{--                                                {!! Form::file('photos[]', array('class' => 'form-control','id'=>'photo','accept'=>'image/*','multiple')) !!}--}}
                                                <input type="file" name="photos[]" id="photo" class="form-control" multiple>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>


                        <!-- END CONTENT BODY -->

                        <!-- END CONTENT -->


                    </div>
                </div>
                @if(auth()->guard('admin')->user()->admin_category_id == 4)
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green" value="حفظ"
                                        onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">
                                    حفظ
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
            <!-- END TAB PORTLET-->


        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description1');
    </script>
    <script>
        CKEDITOR.replace('description2');
    </script>
    <script>
        $(document).ready(function () {
            $("input[name=delivery]").change(function () {

                if ($("#provider").is(':checked')) {
                    $("#delivery_by").show();
                } else {
                    $("#delivery_by").hide();
                    $("#delivery_price").hide();
                }
            });
            $("input[name=delivery_by]").change(function () {

                if ($("#yes").is(':checked')) {
                    $("#delivery_price").show();
                } else {
                    $("#delivery_price").hide();
                }
            });
        });
    </script>

    <script>
        $(".delete_image").click(function () {
            var id = $(this).attr('id');
            var url = '{{ route("imageProductRemove", ":id") }}';

            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {
                    if (!result.message) {
                        $(".img_" + id).fadeOut('1000');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select[name="provider_id"]').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    url: '/admin/get_provider/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#providerActivity').empty();
                        if(data == 'sale')
                        {
                            $('select[name="activity"]').append('<option value="'+data+'">بيع</option>');

                        }else if(data == 'rent')
                        {
                            $('select[name="activity"]').append('<option value="'+data+'">تأجير</option>');

                        }else{
                            $('select[name="activity"]').append('<option value="sale">بيع</option>');
                            $('select[name="activity"]').append('<option value="rent">تأجير</option>');
                        }
                    }
                });



            });
        });
    </script>
@endsection
