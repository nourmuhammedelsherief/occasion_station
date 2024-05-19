<?php

namespace App\Http\Controllers\ProviderController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductModifier;
use Illuminate\Http\Request;

class ProviderProductModifierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $modifiers = $product->modifiers;
        return view('provider.products.modifiers.index' , compact('product' , 'modifiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $product = Product::findOrFail($id);
        return view('provider.products.modifiers.create' , compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , $id)
    {
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'name_ar'          => 'required|string|max:191',
            'name_en'          => 'required|string|max:191',
            'details_ar'       => 'required|string',
            'details_en'       => 'required|string',
            'count'            => 'required|numeric',
        ]);

        // create new modifier
        ProductModifier::create([
            'product_id'    => $product->id,
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'details_ar'    => $request->details_ar,
            'details_en'    => $request->details_en,
            'count'         => $request->count,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('ProviderProductModifier' , $product->id);
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
        $modifier = ProductModifier::findOrFail($id);
        return view('provider.products.modifiers.edit' , compact('modifier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $modifier = ProductModifier::findOrFail($id);
        $this->validate($request , [
            'name_ar'          => 'required|string|max:191',
            'name_en'          => 'required|string|max:191',
            'details_ar'       => 'required|string',
            'details_en'       => 'required|string',
            'count'            => 'required|numeric',
        ]);

        $modifier->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'details_ar'    => $request->details_ar,
            'details_en'    => $request->details_en,
            'count'         => $request->count,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('ProviderProductModifier' , $modifier->product->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modifier = ProductModifier::findOrFail($id);
        $modifier->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('ProviderProductModifier' , $modifier->product->id);
    }
}
