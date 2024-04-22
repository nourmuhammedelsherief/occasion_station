<?php

namespace App\Http\Controllers\AdminController;

use App\Models\AnimatedSlider;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnimatedSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = AnimatedSlider::orderBy('id' , 'desc')->paginate(100);
        return view('admin.animatedSliders.index' , compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.animatedSliders.create');
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
            'photo'   => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ]);
        // create new slider

        AnimatedSlider::create([
            'photo'   => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'slider' , '/uploads/animated_sliders'),
        ]);
        flash('تم أضافه السلايدر المتحرك بنجاح')->success();
        return  redirect()->route('AnimatedSlider');
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
        $slider = AnimatedSlider::findOrFail($id);
        return view('admin.animatedSliders.edit' , compact('slider'));
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
        $slider = AnimatedSlider::findOrFail($id);
        $this->validate($request , [
            'photo'   => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ]);
        // update slider
        $slider->update([
            'photo'   => $request->file('photo') == null ? $slider->photo : UploadImageEdit($request->file('photo') , 'slider' , '/uploads/animated_sliders' , $slider->photo),
        ]);
        flash('تم تعديل السلايدر بنجاح')->success();
        return  redirect()->route('AnimatedSlider');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = AnimatedSlider::findOrFail($id);
        if ($slider->photo != null)
        {
            @unlink(public_path('/uploads/animated_sliders/' . $slider->photo));
        }
        $slider->delete();
        flash('تم حذف السيلايدر بنجاح')->success();
        return  redirect()->route('AnimatedSlider');
    }
}
