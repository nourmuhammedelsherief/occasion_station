<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Category;
use App\Models\City;
use App\Models\Provider;
use App\Models\ProviderCategory;
use App\Models\SubCategory;
use App\Models\ProviderMainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProviderController extends Controller
{
    public function sub_categories($id)
    {
        $sub_categories = SubCategory::whereCategoryId($id)->pluck('id' , 'name');
        return response()->json($sub_categories);
    }

    public function provider_categories($id)
    {
        $provider = Provider::findOrFail($id);
        $categories = ProviderMainCategory::whereProviderId($id)->get();
        return view('admin.providers.categories' , compact('provider' , 'categories'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers  = Provider::orderBy('id' , 'desc')->paginate(100);
        return view('admin.providers.index' , compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $cities = City::all();
        return view('admin.providers.create' , compact('categories' , 'cities'));
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
            'name'    => 'required|string|max:191',
            'city_id' => 'required|exists:cities,id',
            'email'   => 'required|email|unique:providers,email',
            'phone_number' => 'required|unique:providers,phone_number',
            'password' => 'required|string|confirmed|min:6',
            'activity' => 'required|in:sale,rent,both',
            'category_id'=> 'required|exists:categories,id',
//            'category_id*'=> 'exists:categories,id',
//            'sub_categories' => 'sometimes|array',
            'description' => 'required|string',
            // 'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo'   => 'required|mimes:jpg,png,jpeg,gif,tif,bmp,psd|max:5000',
            'logo'    => 'required|mimes:jpg,png,jpeg,gif,tif,bmp,psd|max:5000',
            'bank_payment'   => 'sometimes',
            'online_payment' => 'sometimes',
            'tamara_payment' => 'sometimes',
        ]);
        // create new provider
        $provider = Provider::create([
            'name'   => $request->name,
            'city_id' => $request->city_id,
            'email'  => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'activity' => $request->activity,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'bank_payment'   => $request->bank_payment ?: 'false',
            'online_payment' => $request->online_payment ?: 'false',
            'tamara_payment' => $request->tamara_payment ?: 'false',
//            'category_id' => $request->category_id,
            'photo'   => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/providers'),
            'logo'    => $request->file('logo') == null ? null : UploadImage($request->file('logo') , 'logo' , '/uploads/providers/logos')
        ]);
        // add providers categories
        if ($request->category_id != null)
        {
            ProviderMainCategory::create([
                'provider_id'  => $provider->id,
                'category_id' => $request->category_id,
            ]);
//            foreach ($request->category_id  as $cat)
//            {
//                ProviderMainCategory::create([
//                    'provider_id'  => $provider->id,
//                    'category_id' => $cat,
//                ]);
//            }
        }
//        // create provider sub categories
//        if ($request->sub_categories != null)
//        {
//            foreach ($request->sub_categories  as $sub)
//            {
//                ProviderCategory::create([
//                    'provider_id'  => $provider->id,
//                    'sub_category_id' => $sub,
//                    'active' => '1',
//                ]);
//            }
//        }
        flash('تم أضافه  المزود بنجاح')->success();
        return  redirect()->route('Provider');
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
        $provider = Provider::findOrFail($id);
        $categories = Category::all();
        $sub_categories = $provider->provider_categories;
        $cities = City::all();
        $main_categories = ProviderMainCategory::whereProviderId($provider->id)->get();
        return view('admin.providers.edit' , compact('categories' ,'main_categories','cities','sub_categories', 'provider'));
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
        $provider = Provider::findOrFail($id);
        $this->validate($request , [
            'name'    => 'required|string|max:191',
            'city_id' => 'required|exists:cities,id',
            'email'   => 'required|email|unique:providers,email,'.$id,
            'phone_number' => 'required|unique:providers,phone_number,'.$id,
            'password' => 'nullable|string|confirmed|min:6',
            'activity' => 'required|in:sale,rent,both',
            'category_id'=> 'required|exists:categories,id',
//            'category_id*'=> 'exists:categories,id',
//            'sub_categories' => 'sometimes|array',
            'description' => 'required|string',
            // 'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo'   => 'nullable|mimes:jpg,png,jpeg,gif,tif,bmp,psd|max:5000',
            'logo'    => 'nullable|mimes:jpg,png,jpeg,gif,tif,bmp,psd|max:5000',
            'bank_payment'   => 'sometimes',
            'online_payment' => 'sometimes',
            'tamara_payment' => 'sometimes',
        ]);
        $provider->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'city_id' => $request->city_id,
            'phone_number' => $request->phone_number,
            'password' => $request->password != null ? Hash::make($request->password) : $provider->password,
            'activity' => $request->activity,
            'address' => $request->address,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'bank_payment'   => $request->bank_payment ?: 'false',
            'online_payment' => $request->online_payment ?: 'false',
            'tamara_payment' => $request->tamara_payment ?: 'false',
//
//            'category_id' => $request->category_id,
            'photo'   => $request->file('photo') == null ? $provider->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/providers' , $provider->photo),
            'logo'   => $request->file('logo') == null ? $provider->logo : UploadImageEdit($request->file('logo') , 'logo' , '/uploads/providers/logos' , $provider->logo),
        ]);
        // add providers categories
        if ($request->category_id != null)
        {
            ProviderMainCategory::updateOrCreate(
                ['provider_id'  => $provider->id],
                ['category_id' => $request->category_id]
            );
//            foreach ($request->category_id  as $cat)
//            {
//                $checkCat =  ProviderMainCategory::whereProviderId($provider->id)
//                    ->where('category_id' , $cat)
//                    ->first();
//                if ($checkCat == null)
//                {
//                    ProviderMainCategory::create([
//                        'provider_id'  => $provider->id,
//                        'category_id' => $cat,
//                    ]);
//                }
//            }
        }
//        if ($request->sub_categories != null)
//        {
//            foreach ($request->sub_categories  as $sub)
//            {
//                $checkSub =  ProviderCategory::whereProviderId($provider->id)
//                    ->where('sub_category_id' , $sub)
//                    ->first();
//                if ($checkSub == null)
//                {
//                    ProviderCategory::create([
//                        'provider_id'  => $provider->id,
//                        'sub_category_id' => $sub,
//                        'active' => '1',
//                    ]);
//                }
//            }
//        }
        flash('تم تعديل  المزود بنجاح')->success();
        return  redirect()->route('Provider');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provider = Provider::findOrFail($id);
        if ($provider->photo != null)
        {
            @unlink(public_path('/uploads/providers/' . $provider->photo));
        }
        if ($provider->logo != null)
        {
            @unlink(public_path('/uploads/providers/logos/' . $provider->logo));
        }
        $provider->delete();
        flash('تم حذف  المزود بنجاح')->success();
        return  redirect()->route('Provider');
    }

    public function special($id , $status)
    {
        $provider = Provider::findOrFail($id);
        $provider->update([
            'special'  => $status,
        ]);
        flash('تم التعديل بنجاح')->success();
        return  redirect()->route('Provider');
    }

    public function vip($id , $status)
    {
        $provider = Provider::findOrFail($id);
        $provider->update([
            'vip'  => $status,
        ]);
        flash('تم التعديل بنجاح')->success();
        return  redirect()->route('Provider');
    }


    public function arrange($id)
    {
        $category = ProviderMainCategory::findOrFail($id);
        return view('admin.providers.arrange' , compact('category'));
    }
    public function arrange_submit(Request $request , $id)
    {
        $category = ProviderMainCategory::findOrFail($id);
        $this->validate($request , [
            'arrange' => 'required'
        ]);
        $category->update([
            'arrange' => $request->arrange
        ]);
        flash(trans('تم ترتيب المزود داخل القسم بنجاح'))->success();
        return redirect()->route('provider_categories' , $category->provider_id);
    }
    public function ProviderMainCatRemove($id)
    {
        $deleted = ProviderMainCategory::findOrFail($id);
        $deleted->delete();
        if($deleted){
            $v = '{"message":"done"}';
            return response()->json($v);
        }
    }
    public function ProviderSubCatRemove($id)
    {
        $deleted = ProviderCategory::findOrFail($id);
        $deleted->delete();
        if($deleted){
            $v = '{"message":"done"}';
            return response()->json($v);
        }
    }
    public function stop($id , $state)
    {
        $provider = Provider::findOrFail($id);
        $provider->update([
            'stop' => $state
        ]);
        flash('تم  التعديل بنجاح')->success();
        return redirect()->back();
    }
}
