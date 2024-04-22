<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('id' , 'desc')->paginate(100);
        return view('admin.sliders.index' , compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::select('id' , 'name')->get();
        $products = Product::select('id' , 'name')->get();
        return view('admin.sliders.create' , compact('providers' , 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'title'   => 'required|string|max:5000',
            'photo'   => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'provider_id' => 'sometimes',
            'product_id'  => 'sometimes',
            'outer_url'   => 'sometimes',
        ]);
        // create new slider
        if ($request->provider_id != null && $request->product_id != null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه مزود ومنتج ورابط خارجي للسلايدر أختر واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id == null && $request->product_id != null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه منتج ورابط خارجي للسلايدر أختر واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id != null && $request->product_id == null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه مزود ورابط خارجي للسلايدر أختر  واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id != null && $request->product_id != null && $request->outer_url == null)
        {
            flash('لا يمكنك أضافه مزود  ومنتج للسلايدر أختر  واحد فقط')->error();
            return redirect()->back();
        }elseif($request->provider_id == null && $request->product_id == null && $request->outer_url == null){
            flash('يجب أدخال مزود أو منتج أو رابط خارجي للسلايدر')->error();
            return redirect()->back();
        }
        Slider::create([
            'title'   => $request->title,
            'photo'   => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'slider' , '/uploads/sliders'),
            'provider_id' => $request->provider_id == null ? null : $request->provider_id,
            'product_id'  => $request->product_id == null ? null : $request->product_id,
            'outer_url'   => $request->outer_url == null ? null : $request->outer_url,
        ]);
        flash('تم أضافه السيلايدر بنجاح')->success();
        return  redirect()->route('Slider');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        $providers = Provider::select('id' , 'name')->get();
        $products = Product::select('id' , 'name')->get();
        return view('admin.sliders.edit' , compact('slider' , 'providers' , 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $this->validate($request , [
            'title'   => 'required|string|max:5000',
            'photo'   => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'provider_id' => 'sometimes',
            'product_id'  => 'sometimes',
            'outer_url'   => 'sometimes',
        ]);
        if ($request->provider_id != null && $request->product_id != null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه مزود ومنتج ورابط خارجي للسلايدر أختر واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id == null && $request->product_id != null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه منتج ورابط خارجي للسلايدر أختر واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id != null && $request->product_id == null && $request->outer_url != null)
        {
            flash('لا يمكنك أضافه مزود ورابط خارجي للسلايدر أختر  واحد فقط')->error();
            return redirect()->back();
        }
        elseif ($request->provider_id != null && $request->product_id != null && $request->outer_url == null)
        {
            flash('لا يمكنك أضافه مزود  ومنتج للسلايدر أختر  واحد فقط')->error();
            return redirect()->back();
        }elseif($request->provider_id == null && $request->product_id == null && $request->outer_url == null){
            flash('يجب أدخال مزود أو منتج أو رابط خارجي للسلايدر')->error();
            return redirect()->back();
        }
        // update slider
        $slider->update([
            'title'   => $request->title,
            'photo'   => $request->file('photo') == null ? $slider->photo : UploadImageEdit($request->file('photo') , 'slider' , '/uploads/sliders' , $slider->photo),
            'provider_id' => $request->provider_id == null ? $slider->provider_id : $request->provider_id,
            'product_id'  => $request->product_id == null ? $slider->product_id : $request->product_id,
            'outer_url'   => $request->outer_url == null ? $slider->outer_url : $request->outer_url,
        ]);
        flash('تم تعديل السيلايدر بنجاح')->success();
        return  redirect()->route('Slider');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->photo != null)
        {
            @unlink(public_path('/uploads/sliders/' . $slider->photo));
        }
        $slider->delete();
        flash('تم حذف السيلايدر بنجاح')->success();
        return  redirect()->route('Slider');
    }
}
