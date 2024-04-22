<?php

namespace App\Http\Controllers\AdminController;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = SubCategory::orderBy('id' , 'desc')->paginate(100);
        return  view('admin.sub_categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return  view('admin.sub_categories.create' , compact('categories'));
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
            'name'        => 'required|string|max:191',
            'category_id' => 'required|exists:categories,id',
        ]);
        // create new category
        SubCategory::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);
        flash('تم أضافه  القسم  الفرعي  بنجاح')->success();
        return  redirect()->route('SubCategory');
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
        $category = SubCategory::findOrFail($id);
        $categories = Category::all();
        return  view('admin.sub_categories.edit' , compact('category' , 'categories'));
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
        $category = SubCategory::findOrFail($id);
        $this->validate($request , [
            'name'        => 'required|string|max:191',
            'category_id' => 'required|exists:categories,id',
        ]);
        // update sub category
        $category->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);
        flash('تم تعديل  القسم  الفرعي  بنجاح')->success();
        return  redirect()->route('SubCategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = SubCategory::findOrFail($id);
        if ($category->provider_sub_categories->count() > 0)
        {
            flash('لا يمكنك حذف هذا  القسم لأنه مستخدم')->error();
            return  redirect()->route('SubCategory');
        }
        $category->delete();
        flash('تم حذف  القسم  الفرعي  بنجاح')->success();
        return  redirect()->route('SubCategory');
    }
}
