<?php

namespace App\Http\Controllers\AdminController;

use App\Models\AdminCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_categories = AdminCategory::orderBy('id' , 'desc')->get();
        return view('admin.admin_categories.index' , compact('admin_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin_categories.create');
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
            'name'  => 'required|string|max:191',
        ]);
        // create new admin categories
        AdminCategory::create([
            'name'  => $request->name,
        ]);
        flash('تم أنشاء  القسم  بنجاح')->success();
        return redirect()->route('AdminCategory');
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
        $cat = AdminCategory::findOrFail($id);
        return view('admin.admin_categories.edit' , compact('cat'));
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
        $cat = AdminCategory::findOrFail($id);
        $this->validate($request , [
            'name'  => 'required|string|max:191',
        ]);
        // create new admin categories
        $cat->update([
            'name'  => $request->name,
        ]);
        flash('تم تعديل  القسم  بنجاح')->success();
        return redirect()->route('AdminCategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = AdminCategory::findOrFail($id);
        $cat->delete();
        flash('تم حذف  القسم  بنجاح')->success();
        return redirect()->route('AdminCategory');

    }
}
