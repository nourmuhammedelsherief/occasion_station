<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Provider;
use App\Models\ProviderSlider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $provider = Provider::findOrFail($id);
        $sliders = ProviderSlider::whereProviderId($provider->id)
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('admin.providers.sliders.index' , compact('sliders' , 'provider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $provider = Provider::findOrFail($id);
        return view('admin.providers.sliders.create' , compact('provider'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $provider = Provider::findOrFail($id);
        $this->validate($request , [
            'photo'   => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ]);
        ProviderSlider::create([
            'photo'   => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'slider' , '/uploads/providers/sliders'),
            'provider_id' => $provider->id,
        ]);
        flash('تم أضافه السلايدر الي المزود بنجاح')->success();
        return  redirect()->route('providerSlider' , $provider->id);
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
        $slider = ProviderSlider::findOrFail($id);
        return view('admin.providers.sliders.edit' , compact('slider'));
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
        $slider = ProviderSlider::findOrFail($id);
        $this->validate($request , [
            'photo'   => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
        ]);
        // update slider
        if ($request->file('photo') != null and ($slider->photo == 'default1.jpg' or $slider->photo == 'default2.jpg'))
        {
            $slider->update([
                'photo'   => $request->file('photo') == null ? $slider->photo : UploadImage($request->file('photo') , 'slider' , '/uploads/providers/sliders'),
            ]);
        }else{
            $slider->update([
                'photo'   => $request->file('photo') == null ? $slider->photo : UploadImageEdit($request->file('photo') , 'slider' , '/uploads/providers/sliders' , $slider->photo),
            ]);
        }
        flash('تم تعديل السلايدر بنجاح')->success();
        return  redirect()->route('providerSlider' , $slider->provider->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = ProviderSlider::findOrFail($id);
        if ($slider->photo != null)
        {
            if ($slider->photo != 'default1.jpg' and $slider->photo != 'default2.jpg')
            {
                @unlink(public_path('/uploads/providers/sliders/' . $slider->photo));
            }
        }
        $slider->delete();
        flash('تم حذف السلايدر بنجاح')->success();
        return  redirect()->route('providerSlider' , $slider->provider->id);
    }
}
