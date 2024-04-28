<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Electronic_wallet;
use App\Models\History;
use App\Models\Product;
use App\Models\Setting;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Image;
use Auth;
use App\Models\Permission;

class SettingController extends Controller
{
    //
    public function index()
    {
        $settings = settings();
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'bearer_token' => 'required|string|max:191',
            'sender_name' => 'required|string|max:191',
            // 'commission' => 'required',
            'contact_number' => 'required',
            'tax' => 'required',
            // 'delivery_price' => 'sometimes',
            'advisor_number' => 'sometimes',
            // 'search_range' => 'required',
            'myFatoourah_token' => 'required',
            'contact_text' => 'nullable',
        ]);

        Setting::where('id', 1)->first()->update($request->all());
        return Redirect::back()->with('success', 'تم حفظ البيانات بنجاح');
    }


}
