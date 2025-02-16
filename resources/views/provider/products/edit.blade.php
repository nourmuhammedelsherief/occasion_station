@extends('provider.layouts.master')

@section('title')
    منتجاتي
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
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
                <span>عرض  منتجاتي</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض منتجاتي
        <small> منتجاتي</small>
    </h1>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-8">
            <!-- BEGIN TAB PORTLET-->
            <form method="post" action="{{route('updateMyProduct' , $product->id)}}" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>
                <div class="portlet light bordered table-responsive">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-anchor font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase"> تعديل منتجاتي</span>
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
                                                <label class="col-md-3 control-label">أسم المنتج </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control"
                                                           placeholder="أكتب أسم  المنتج" value="{{$product->name}}"
                                                           required>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> سعر المنتج قبل الخصم </label>
                                                <div class="col-md-9">
                                                    <input type="number" name="price_before_discount" class="form-control"
                                                           placeholder="أضف سعر المنتج قبل الخصم" value="{{$product->price_before_discount}}"
                                                           required>
                                                    @if ($errors->has('price_before_discount'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('price_before_discount') }}</strong>
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
                                                           placeholder="أدخل أقل كميه للطلب من  المنتج" value="{{$product->less_amount}}"
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
                                                    <select name="activity" class="form-control" required>
                                                        <option disabled selected> أختر النشاط للمزود</option>
                                                        <option value="sale" {{$product->activity == 'sale' ? 'selected' : ''}}> بيع</option>
                                                        <option value="rent" {{$product->activity == 'rent' ? 'selected' : ''}}> تأجير</option>
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
                                                    <textarea name="description" id="description1" class="form-control" placeholder="اكتب تفاصيل  المنتج">
                                                        {{$product->description}}
                                                    </textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> أحتياجات المنتج </label>
                                                <div class="col-md-9">
                                                    <textarea name="product_requirements" id="description2" class="form-control" placeholder="اكتب أحتياجات المنتج">
                                                        {{$product->product_requirements}}
                                                    </textarea>
                                                    @if ($errors->has('product_requirements'))
                                                        <span class="help-block">
                                                            <strong style="color: red;">{{ $errors->first('product_requirements') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"> هل يوجد توصيل </label>
                                                <div class="col-md-9">
                                                    <input type="radio" name="delivery" id="yes"  value="yes" {{$product->delivery == 'yes' ? 'checked' : ''}}> نعم
                                                    <input type="radio" name="delivery"  value="no" {{$product->delivery == 'no' ? 'checked' : ''}}> لا
                                                    @if ($errors->has('delivery'))
                                                        <span class="help-block">
                                               <strong style="color: red;">{{ $errors->first('delivery') }}</strong>
                                            </span>
                                                    @endif
                                                </div>
                                            </div>
                                            {{--                                            <div class="form-group" id="delivery_price" style="display: {{$product->delivery == 'yes' ? 'block': 'none'}}">--}}
                                            {{--                                                <label class="col-md-3 control-label">سعر التوصيل </label>--}}
                                            {{--                                                <div class="col-md-9">--}}
                                            {{--                                                    <input type="number" name="delivery_price"  class="form-control"--}}
                                            {{--                                                           placeholder="أكتب سعر التوصيل" value="{{$product->delivery_price}}"--}}
                                            {{--                                                           required>--}}
                                            {{--                                                    @if ($errors->has('delivery_price'))--}}
                                            {{--                                                        <span class="help-block">--}}
                                            {{--                                                            <strong style="color: red;">{{ $errors->first('delivery_price') }}</strong>--}}
                                            {{--                                                        </span>--}}
                                            {{--                                                    @endif--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label col-md-3">
                                                        صور  المنتج
                                                    </label>
                                                    @foreach($images as $image)
                                                        <div class="col-md-3 test text-center col-sm-4 img_{{ $image->id }}">
                                                            <p><img src="{{ URL::to('uploads/products/'.$image->photo) }}" class="img-fluid" height="150" id="file_name"></p>
                                                            <a  id="{{ $image->id }}"  style="color: white;text-decoration: none;" class="delete_image hideDiv btn btn-danger">
                                                                <i class="glyphicon glyphicon-trash "></i> مسح</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                {!! Form::file('photos[]', array('class' => 'form-control','id'=>'photo','accept'=>'image/*','multiple')) !!}
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
        $(document).ready(function () {
            $("input[name=delivery]").change(function(){

                if($("#yes").is(':checked')){
                    $("#delivery_price").show();
                }else{
                    $("#delivery_price").hide();
                }
            });
        });
    </script>
    <script>
        $(".delete_image").click(function(){
            var id = $(this).attr('id');
            var url = '{{ route("imageProductRemove", ":id") }}';

            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(result) {
                    if (!result.message)
                    {
                        $(".img_"+id).fadeOut('1000');
                    }

                }
            });
        });
    </script>
@endsection
