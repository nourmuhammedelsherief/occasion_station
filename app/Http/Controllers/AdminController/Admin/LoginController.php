<?php

namespace App\Http\Controllers\AdminController\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showLoginForm()
    {
        return view('admin.authAdmin.login');
    }
    public function login(Request $request)
    {
        App::setLocale('ar');
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);
        // Verified - send email
        $credential =[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if (Auth::guard('admin')->attempt($credential, $request->member)){
            if (Auth::guard('admin')->user()->admin_category_id == 4)
            {
                return redirect()->intended(route('admin.home'));
            }else{
                return redirect()->intended(route('employees.home'));
            }
        }
        return redirect()->back()->withInput($request->only(['email','remember']))->with('warning_login', trans('messages.warning_login'));



    }

    public function logout(Request $request)
    {
//        dd(Auth::guard('admin')->user()->id);
        if (Auth::guard('admin')->user()->admin_category_id == 4)
        {
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }else{
            Auth::guard('admin')->logout();
            return redirect('/employees/login');
        }
    }
}
