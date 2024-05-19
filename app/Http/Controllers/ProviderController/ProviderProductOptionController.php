<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class ProviderProductOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $options = $product->options;
        return view('provider.products.options.index' , compact('product' , 'options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $product = Product::findOrFail($id);
        $modifiers = $product->modifiers;
        return view('provider.products.options.create' , compact('product' , 'modifiers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , $id)
    {
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'modifier_id' => 'required',
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'price'    => 'required|numeric',
        ]);

        // create new options
        ProductOption::create([
            'modifier_id' => $request->modifier_id,
            'product_id'  => $product->id,
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'price'       => $request->price,
            'active'      => 'true',
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('ProviderProductOption' , $product->id);
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
        $option = ProductOption::findOrFail($id);
        $modifiers = $option->product->modifiers;
        return view('provider.products.options.edit' , compact('option' , 'modifiers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $option = ProductOption::findOrFail($id);
        $this->validate($request , [
            'modifier_id' => 'required',
            'name_ar'  => 'required|string|max:191',
            'name_en'  => 'required|string|max:191',
            'price'    => 'required|numeric',
        ]);

        $option->update([
            'modifier_id' => $request->modifier_id,
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'price'       => $request->price,
            'active'      => 'true',
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('ProviderProductOption' , $option->product->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $option = ProductOption::findOrFail($id);
        $option->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('ProviderProductOption' , $option->product->id);
    }
}
