<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ProductCategory::orderBy('id' , 'desc')->paginate(100);
        return view('admin.product_categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product_categories.create');
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
            'name'   => 'required',
        ]);
        // store new  category
        ProductCategory::create([
            'name'   => $request->name,
        ]);
        flash('تم أضافه  القسم  بنجاح')->success();
        return redirect()->route('ProductCategory');
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
        $category = ProductCategory::findOrFail($id);
        return view('admin.product_categories.edit' , compact('category'));
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
        $category = ProductCategory::findOrFail($id);
        $this->validate($request , [
            'name'   => 'required',
        ]);
        $category->update([
            'name'   => $request->name,
        ]);
        flash('تم تعديل  القسم  بنجاح')->success();
        return redirect()->route('ProductCategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();
        flash('تم حذف  القسم  بنجاح')->success();
        return redirect()->route('ProductCategory');

    }
}
