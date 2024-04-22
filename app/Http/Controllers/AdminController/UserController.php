<?php

namespace App\Http\Controllers\AdminController;

use App\Models\City;

use App\Models\Country;
use App\Models\FoodCategory;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Models\UserDepartment;
use Auth;
use Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(100);
        return view('admin.users.customers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        if ($type == 'user') {
            return view('admin.users.create');
        } elseif ($type == 'customer') {
            return view('admin.users.customers.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * type customer
     * type user
     */
    public function store(Request $request, $type)
    {
        if ($type == 'user') {
            // create user
            $this->validate($request, [
                'phone_number' => 'required|unique:users',
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|max:255',
                'photo' => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required|same:password',
                'active' => 'required',
            ]);

            // end certificate_photo
            $user = User::create([
                'phone_number' => $request->phone_number,
                'country_id' => $request->country_id,
                'name' => $request->name,
                'active' => $request->active,
                'password' => Hash::make($request->password),
                'photo' => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/users'),
                'type' => 1,
                'api_token' => $request->token,
            ]);
            flash('تم أنشاء المستخدم بنجاح')->success();
//            return redirect('admin/users/1');
            return redirect('admin/users/1');

        } elseif ($type == 'customer') {
            // create customer
            $this->validate($request, [
                'phone_number' => 'required|unique:users',
                'email' => 'nullable|email|unique:users',
                'name' => 'required|max:191',
                'active' => 'required',
                'photo' => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required|same:password',

            ]);

            $user = User::create([
                'phone_number' => $request->phone_number,
                'email' => $request->email == null ? null : $request->email,
                'name' => $request->name,
                'active' => $request->active,
                'password' => Hash::make($request->password),
                'photo' => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/users'),
            ]);

            flash('تم أنشاء العميل  بنجاح')->success();
            return redirect('admin/users/customer');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrfail($id);
        return view('admin.users.customers.edit', compact( 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phone_number' => 'required|unique:users,phone_number,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'name' => 'required|max:191',
            'photo' => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',

        ]);
        $user = User::findOrFail($id);
        $user->update([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'email' => $request->email == null ? $user->email : $request->email,
            'photo' => $request->file('photo') == null ? $user->photo : UploadImageEdit($request->file('photo'), 'photo', '/uploads/users' , $user->photo),
        ]);
        flash('تم تعديل بيانات العميل')->success();
        return redirect('admin/users/customer');
    }

    public function update_pass(Request $request, $id)
    {
        //
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',

        ]);
        $users = User::findOrfail($id);
        $users->password = Hash::make($request->password);

        $users->save();

        return redirect()->back()->with('information', 'تم تعديل كلمة المرور المستخدم');
    }

    public function update_privacy(Request $request, $id)
    {
        //
        $this->validate($request, [
            'active' => 'required',

        ]);
        $users = User::findOrfail($id);
        $users->active = $request->active;
        $users->save();

        return redirect()->back()->with('information', 'تم تعديل اعدادات المستخدم');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->photo != null) {
            if (file_exists(public_path('uploads/users/' . $user->photo))) {
                unlink(public_path('uploads/users/' . $user->photo));
            }
        }

        $user->delete();
        flash('تم الحذف بنجاح')->success();
        return back();
    }



    public function active_user($id, $active)
    {
        $user = User::findOrFail($id);
        $user->update([
            'active' => $active
        ]);
        flash('تم تغيير الخصوصية بنجاح')->success();
        return redirect()->back();
    }
}
