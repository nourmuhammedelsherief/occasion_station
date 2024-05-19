<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPhoto;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use function base_path;
use function flash;
use function public_path;
use function redirect;
use function response;
use function view;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::whereProviderId(Auth::guard('provider')->user()->id)
            ->whereAccepted('true')
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('provider.products.index' , compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();
        $categories = ProductCategory::all();
        return view('provider.products.create' , compact('providers' , 'categories'));
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
            'name'                 => 'required|string|max:191',
            'name_en'              => 'required|string|max:191',
            'category_id'          => 'required|exists:product_categories,id',
            'activity'             => 'required|in:rent,sale',
            'description'          => 'sometimes|string',
            'description_en'       => 'sometimes|string',
            'price'                => 'required',
            'price_before_discount'=> 'nullable',
            'less_amount'          => 'required',
            'product_requirements' => 'sometimes|string',
            'product_requirements_en' => 'sometimes|string',
//            'delivery'             => 'required|in:yes,no',
//            'delivery_price'       => 'required_if:delivery,yes',
            'photos'               => 'sometimes|array',
            'photos*'              => 'mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000'
        ]);
        // create new product
        $product = Product::create([
            'provider_id'          => Auth::guard('provider')->user()->id,
            'category_id'          => $request->category_id,
            'name'                 => $request->name,
            'name_en'              => $request->name_en,
            'activity'             => $request->activity,
            'description'          => $request->description,
            'description_en'       => $request->description_en,
            'price'                => $request->price,
            'price_before_discount'=> $request->price_before_discount,
            'less_amount'          => $request->less_amount,
            'product_requirements' => $request->product_requirements,
            'product_requirements_en' => $request->product_requirements_en,
//            'delivery'             => $request->delivery,
            'accepted'             => 'false',
//            'delivery_price'       => $request->delivery_price,
        ]);
        // create product photos if found
        $files = $request->file('photos');
        if ($files != "") {
            foreach ($files as $photo) {
                $images = new ProductPhoto();
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $photo->getClientOriginalExtension();
                $path = base_path() . "/public/uploads/products";
                $images->product_id = $product->id;
                $images->photo = $fileFinalName_ar;
                $images->save();
                $destinationPath = public_path() . '/uploads/products';
                $img = Image::make($photo->getRealPath());
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $fileFinalName_ar);
            }
        }
        flash(trans('messages.productAdded'))->success();
        return redirect()->route('MyProduct');
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
        $product = Product::findOrFail($id);
        $providers = Provider::all();
        $images = $product->photos;
        return view('provider.products.edit' , compact('product' ,'providers' , 'images'));
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
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'name'                 => 'required|string|max:191',
            'activity'             => 'required|in:rent,sale',
            'description'          => 'sometimes|string',
            'price'                => 'required',
            'less_amount'          => 'required',
            'product_requirements' => 'required',
            'delivery'             => 'required|in:yes,no',
//            'delivery_price'       => 'required_if:delivery,yes',
            'photos'               => 'sometimes|array',
            'photos*'              => 'mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000'
        ]);
        $product->update([
            'name'                 => $request->name,
            'activity'             => $request->activity,
            'description'          => $request->description == null ? $product->description : $request->description,
            'price'                => $request->price,
            'less_amount'          => $request->less_amount,
            'product_requirements' => $request->product_requirements,
            'delivery'             => $request->delivery,
//            'delivery_price'       => $request->delivery_price,
        ]);
        // create product photos if found
        $files = $request->file('photos');
        if ($files != "") {
            foreach ($files as $photo) {
                $images = new ProductPhoto();
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $photo->getClientOriginalExtension();
                $path = base_path() . "/public/uploads/products";
                $images->product_id = $product->id;
                $images->photo = $fileFinalName_ar;
                $images->save();
                $destinationPath = public_path() . '/uploads/products';
                $img = Image::make($photo->getRealPath());
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $fileFinalName_ar);
            }
        }
        flash('تم تعديل المنتج بنجاح')->success();
        return redirect()->route('MyProduct');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // delete product photos
        if ($product->photos->count() > 0)
        {
            foreach ($product->photos as $photo)
            {
                @unlink(public_path('/uploads/products/' . $photo->photo));
                $photo->delete();
            }
        }
        $product->delete();
        flash('تم حذف المنتج بنجاح')->success();
        return redirect()->route('MyProduct');

    }

    public function imageProductRemove($id)
    {
        $deleted = ProductPhoto::findOrFail($id);
        // remove photo Source
        @unlink(public_path('/uploads/products/'.$deleted->photo));
        $deleted->delete();
        if($deleted){
            $v = '{"message":"done"}';
            return response()->json($v);
        }
    }
}
