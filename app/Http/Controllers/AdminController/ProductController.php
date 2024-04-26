<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPhoto;
use App\Models\Provider;
use App\Models\ProviderProductCategory;
use App\Models\Setting;
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
        $products = Product::orderBy('id' , 'desc')
            ->where('recomended' , 'false')
            ->whereAccepted('true')
            ->paginate(100);
        return view('admin.products.index' , compact('products'));
    }

    public function recomended_products()
    {
        $products = Product::orderBy('id' , 'desc')
            ->where('recomended' , 'true')
            ->whereAccepted('true')
            ->paginate(100);
        return view('admin.products.recomended' , compact('products'));
    }
    public function waiting_accept_products()
    {
        $products = Product::whereAccepted('false')
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('admin.products.waiting_accept' , compact('products'));
    }

    public function AcceptProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'accepted' => 'true',
        ]);
        flash('تم قبول المنتج بنجاح')->success();
        return redirect()->back();
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
        return view('admin.products.create' , compact('providers' , 'categories'));
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
            'provider_id'          => 'required|exists:providers,id',
            'category_id'          => 'required|exists:product_categories,id',
            'activity'             => 'required|in:rent,sale',
            'description'          => 'sometimes|string',
            'price'                => 'required',
            'price_before_discount'=> 'nullable',
            'less_amount'          => 'required',
            'product_requirements' => 'required',
            'delivery'             => 'required|in:yes,no',
            'delivery_by'          => 'required_if:delivery,no|in:provider,app',
//            'delivery_price'       => 'required_if:delivery_by,app',
            'store_receiving'       => 'required_if:delivery_by,app|in:true,false',
            'photos'               => 'required|array',
            'photos*'              => 'mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000'
        ]);
        // create new product
        $product = Product::create([
            'provider_id'          => $request->provider_id,
            'category_id'          => $request->category_id,
            'name'                 => $request->name,
            'activity'             => $request->activity,
            'description'          => $request->description,
            'price'                => $request->price,
            'price_before_discount'=> $request->price_before_discount,
            'less_amount'          => $request->less_amount,
            'product_requirements' => $request->product_requirements,
            'store_receiving'      => $request->store_receiving == null ? 'false' : $request->store_receiving,
            'delivery'             => $request->delivery,
            'delivery_by'          => $request->delivery_by == null ? null : $request->delivery_by,
            'delivery_price'       => $request->delivery_price == null ? Setting::first()->delivery_price : $request->delivery_price,
        ]);

        ProviderProductCategory::updateOrCreate(
            [
                'provider_id' => $request->provider_id,
                'category_id' => $request->category_id,
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
        flash('تم أضافه  المنتج بنجاح')->success();
        return redirect()->route('Product');
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
        $categories = ProductCategory::all();
        return view('admin.products.edit' , compact('product' ,'categories','providers' , 'images'));
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
            'provider_id'          => 'required|exists:providers,id',
            'category_id'          => 'required|exists:product_categories,id',
            'name'                 => 'required|string|max:191',
            'activity'             => 'required|in:rent,sale',
            'description'          => 'sometimes|string',
            'price'                => 'required',
            'price_before_discount'=> 'nullable',
            'less_amount'          => 'required',
            'product_requirements' => 'required',
//            'store_receiving'      => 'required|in:true,false',
            'delivery'             => 'required|in:yes,no',
            'delivery_by'          => 'required_if:delivery,no|in:provider,app',
            'store_receiving'       => 'required_if:delivery_by,app|in:true,false',
            'photos'               => 'sometimes|array',
            'photos*'              => 'mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000'
        ]);
        $product->update([
            'provider_id'          => $request->provider_id,
            'category_id'          => $request->category_id,
            'name'                 => $request->name,
            'activity'             => $request->activity,
            'description'          => $request->description == null ? $product->description : $request->description,
            'price'                => $request->price,
            'price_before_discount'=> $request->price_before_discount == null ? $product->price_before_discount : $request->price_before_discount,
            'less_amount'          => $request->less_amount,
            'product_requirements' => $request->product_requirements,
            'delivery'             => $request->delivery,
            'store_receiving'      => $request->store_receiving == null ? $product->store_receiving : $request->store_receiving,
            'delivery_by'          => $request->delivery_by == null ? $product->delivery_by : $request->delivery_by,
            'delivery_price'       => $request->delivery_price == null ? Setting::first()->delivery_price : $request->delivery_price,
        ]);
        ProviderProductCategory::updateOrCreate(
            [
                'provider_id' => $request->provider_id,
                'category_id' => $request->category_id,
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
        return redirect()->route('Product');
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
        return redirect()->route('Product');

    }

    public function recommend($id , $status)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'recomended' => $status
        ]);
        flash('تم  التعديل بنجاح')->success();
        if ($status == 'true')
        {
            return redirect()->route('recomended_products');
        }else{
            return redirect()->route('Product');
        }
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
    public function get_provider($id)
    {
        $provider = Provider::find($id);
        return response()->json($provider->activity);
    }
    public function stop($id , $state)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'stop' => $state
        ]);
        flash('تم  التعديل بنجاح')->success();
        return redirect()->back();
    }
}
