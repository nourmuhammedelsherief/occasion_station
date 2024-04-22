<?php

namespace App\Http\Controllers\AdminController;

use App\Models\ProviderRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProviderRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = 'new')
    {
        $providers = ProviderRegister::whereStatus($status)
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('admin.provider_register.index' , compact('providers' , 'status'));
    }
    public function complete_provider($id , $status)
    {
        $provider = ProviderRegister::findOrFail($id);
        if ($status == 'completed')
        {
            $provider->update([
                'status' => $status,
            ]);
            flash('تم التعديل بنجاح')->success();
            return redirect()->to(url('/admin/provider_registers/' . $status));
        }elseif ($status == 'canceled')
        {
            return view('admin.provider_register.cancel' , compact('provider' , 'status'));
        }
    }

    public function cancel_provider(Request $request , $id,$status)
    {
        $this->validate($request , [
            'cancel_reason' => 'sometimes|string'
        ]);
        $provider = ProviderRegister::findOrFail($id);
        $provider->update([
            'status' => $status,
            'cancel_reason' => $request->cancel_reason,
        ]);
        flash('تم التعديل بنجاح')->success();
        return redirect()->to(url('/admin/provider_registers/' . $status));
    }

}
