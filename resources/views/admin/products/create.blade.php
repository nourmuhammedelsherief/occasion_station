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
            <form method="post" action="{{route('storeProduct')}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> أضافه المنتجات</span>
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
                                                                value="{{$provider->id}}"> {{$provider->name}} </option>
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
                                                                value="{{$category->id}}"> {{$category->name}} </option>
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
                                                <label class="col-md-3 control-label">أسم المنتج </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control"
                                                           placeholder="أكتب أسم  المنتج" value="{{old('name')}}"
                                                           required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">سعر المنتج قبل الخصم</label>
                                                <div class="col-md-9">
                                                    <input type="number" name="price_before_discount"
                                                           class="form-control"
                                                           placeholder="أضف سعر المنتج قبل الخصم"
                                                           value="{{old('price_before_discount')}}"
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
                                                           placeholder="أكتب سعر المنتج" value="{{old('price')}}"
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
                                                           value="{{old('less_amount')}}"
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
                                                    <textarea name="description" rows="5" class="form-control"
                                                              placeholder="اكتب تفاصيل  المنتج"></textarea>
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
                                                    <textarea rows="5" name="product_requirements"
                                                              class="form-control"
                                                              placeholder="اكتب أحتياجات المنتج"></textarea>
                                                    @if ($errors->has('product_requirements'))
                                                        <span class="help-block">
                                                            <strong
                                                                style="color: red;">{{ $errors->first('product_requirements') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--                                            <div class="form-group" >--}}
                                            {{--                                                <label class="col-md-3 control-label">سعر التوصيل </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <input type="number" name="delivery_price" class="form-control"--}}
                                            {{--                                                           placeholder="أكتب سعر التوصيل"--}}
                                            {{--                                                           value="{{old('delivery_price')}}"--}}
                                            {{--                                                           required>--}}
                                            {{--                                                    @if ($errors->has('delivery_price'))--}}
                                            {{--                                                        <span class="help-block">--}}
                                            {{--                                                            <strong--}}
                                            {{--                                                                style="color: red;">{{ $errors->first('delivery_price') }}</strong>--}}
                                            {{--                                                        </span>--}}
                                            {{--                                                    @endif--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            <div class="form-group">
                                                <label class="col-md-3 control-label col-md-3">
                                                    أدخل صور المنتج
                                                </label>
                                                <div class="col-md-9">
{{--                                                    {!! Form::file('photos[]', array('class' => ' form-control','id'=>'photo','accept'=>'image/*','multiple')) !!}--}}
                                                    <input type="file" name="photos[]" id="photo" class="form-control" multiple>
                                                    @if ($errors->has('photos'))
                                                        <span class="help-block">
                                                                 <strong
                                                                     style="color: red;">{{ $errors->first('photos') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>
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
