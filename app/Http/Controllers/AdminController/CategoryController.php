<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id' , 'desc')->paginate(100);
        return view('admin.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
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
            'icon'   => 'required|mimes:jpg,png,gif,tif,bmp,psd,jpeg|max:5000',
        ]);
        // store new  category
        Category::create([
            'name'   => $request->name,
            'icon'   => $request->file('icon') == null ? null : UploadImage($request->file('icon') , 'icon' , '/uploads/categories'),
        ]);
        flash('تم أضافه  القسم  بنجاح')->success();
        return redirect()->route('Category');
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
        $category = Category::findOrFail($id);
        return view('admin.categories.edit' , compact('category'));
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
        $category = Category::findOrFail($id);
        $this->validate($request , [
            'name'   => 'required',
            'icon'   => 'nullable|mimes:jpg,png,gif,tif,bmp,psd,jpeg|max:5000',
        ]);
        $category->update([
            'name'   => $request->name,
            'icon'   => $request->file('icon') == null ? $category->icon : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/categories' , $category->icon),
        ]);
        flash('تم تعديل  القسم  بنجاح')->success();
        return redirect()->route('Category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->icon != null)
        {
            @unlink(public_path('/uploads/categories/' . $category->icon));
        }
        $category->delete();
        flash('تم حذف  القسم  بنجاح')->success();
        return redirect()->route('Category');

    }

    public function arrange($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.arrange' , compact('category'));
    }
    public function arrange_submit(Request $request , $id)
    {
        $category = Category::findOrFail($id);
        $this->validate($request , [
            'arrange' => 'required'
        ]);
        $category->update([
            'arrange' => $request->arrange
        ]);
        flash(trans('تم ترتيب القسم بنجاح'))->success();
        return redirect()->route('Category');

    }
}
