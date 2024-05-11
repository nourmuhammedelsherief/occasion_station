<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProviderProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $sizes = $product->sizes;
        return view('provider.products.sizes.index' , compact('product' , 'sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $product = Product::findOrFail($id);
        return view('provider.products.sizes.create' , compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , $id)
    {
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'price'    => 'required|numeric',
        ]);
        // create new size
        ProductSize::create([
            'product_id'  => $product->id,
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'price'       => $request->price,
            'active'      => 'true',
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('ProviderProductSize' , $product->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $size = ProductSize::findOrFail($id);
        return view('provider.products.sizes.edit' , compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $size = ProductSize::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'price'    => 'required|numeric',
        ]);
        // create new size
        $size->update([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'price'       => $request->price,
            'active'      => 'true',
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('ProviderProductSize' , $size->product->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $size = ProductSize::findOrFail($id);
        $size->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('ProviderProductSize' , $size->product->id);
    }
}
