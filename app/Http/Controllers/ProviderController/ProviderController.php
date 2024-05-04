<?php

namespace App\Http\Controllers\ProviderController;

use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\Promise\all;

class ProviderController extends Controller
{
    public function my_profile()
    {
        $data = Provider::find(Auth::id());
        return view('provider.admins.profile.profile', compact('data'));
    }

    public function my_profile_edit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'email' => 'required|string|email|max:255|unique:providers,email,' . Auth::id(),
//            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone_number' => 'required',
            'latitude'      => 'nullable',
            'longitude'      => 'nullable',
            'delivery'             => 'required|in:true,false',
            'delivery_by'          => 'required_if:delivery,true|in:provider,app',
            'delivery_price'       => 'required_if:delivery_by,provider',
            'store_receiving'      => 'required_if:delivery_by,provider|in:true,false',
        ]);
        $data = Provider::find(Auth::id());
        $data->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'latitude' => $request->latitude ? $request->latitude : $data->latitude,
            'longitude' => $request->longitude? $request->longitude : $data->longitude,
            'store_receiving'      => $request->store_receiving ?: 'false',
            'delivery'             => $request->delivery,
            'delivery_by'          => $request->delivery_by?:'app',
            'delivery_price'       => $request->delivery_price == null ? Setting::first()->delivery_price : $request->delivery_price,
        ]);
        return redirect(url('/provider/profile'))->with('msg', 'تم التعديل بنجاح');

    }

    public function change_pass()
    {

        return view('provider.admins.profile.change_pass');

    }

    public function change_pass_update(Request $request)
    {
        $this->validate($request, [

            'password' => 'required|string|min:6|confirmed',

        ]);


        $updated = Provider::where('id', Auth::id())->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect(url('/provider/profileChangePass'))->with('msg', 'تم التعديل بنجاح');
    }

    public function index()
    {
        $data = Provider::all();
        return view('provider.admins.admins.index', compact('data'));
    }

    public function create()
    {
        return view('provider.admins.admins.create');
    }

    public function edit($id)
    {
        $data = Provider::find($id);
        return view('provider.admins.admins.edit', compact('data'));
    }

    public function store(Request $request)
    {
//        dd($request->role);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);


        $request['remember_token'] = Str::random(60);
        $request['password'] = Hash::make($request->password);
        Provider::create($request->all());

        return redirect(url('/provider/admins'))->with('msg', 'تم الاضافه بنجاح');
    }

    public function update(Request $request, $id)
    {
//        dd($request->role);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $id,
//            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);


        $request['remember_token'] = Str::random(60);
//        $request['password'] = Hash::make($request->password);

        Provider::where('id', $id)->first()->update($request->all());

        return redirect(url('/provider/admins'))->with('msg', 'تم التعديل بنجاح');
    }

    public function admin_delete($id)
    {
        Provider::where('id', $id)->delete();
        return back()->with('msg', 'تم الحذف بنجاح');
    }

}
