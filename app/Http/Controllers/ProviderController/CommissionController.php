<?php

namespace App\Http\Controllers\ProviderController;

use App\Models\ProviderCommissionHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = ProviderCommissionHistory::whereProviderId(Auth::guard('provider')->user()->id)->paginate(100);
        return view('provider.commissions.index' , compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('provider.commissions.create');
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
            'transfer_photo'  => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'amount'          => 'required',
        ]);
        // create new record
        ProviderCommissionHistory::create([
            'provider_id'    => Auth::guard('provider')->user()->id,
            'amount'         => $request->amount,
            'transfer_photo' => UploadImage($request->file('transfer_photo') , 'photo' , '/uploads/transfers'),
            'invoice_id'     => null,
            'status'         => 'wait',
        ]);
        flash('تم أرسال العموله الي الأدراه بنجاح بأنتظار التأكيد')->success();
        return  redirect()->route('MyCommission');
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
        $commission = ProviderCommissionHistory::findOrFail($id);
        return view('provider.commissions.edit' , compact('commission'));
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
        $commission = ProviderCommissionHistory::findOrFail($id);
        $this->validate($request , [
            'transfer_photo'  => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'amount'          => 'required',
        ]);
        $commission->update([
            'amount'         => $request->amount,
            'transfer_photo' => $request->file('transfer_photo') == null ? $commission->transfer_photo : UploadImageEdit($request->file('transfer_photo') , 'photo' , '/uploads/transfers' , $commission->transfer_photo),
            'invoice_id'     => null,
            'status'         => 'wait',
        ]);
        flash(' تم تعديل العموله بنجاح بأنتظار التأكيد')->success();
        return  redirect()->route('MyCommission');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commission = ProviderCommissionHistory::findOrFail($id);
        $provider = Auth::guard('provider')->user();
        if ($commission->transfer_photo != null)
        {
            @unlink(public_path('/uploads/transfers/' . $commission->transfer_photo));
        }
//        if ($commission->status == 'done')
//        {
//            $provider_commission = $provider->commission - $commission->amount;
//            $provider->update([
//                'commission' => $provider_commission,
//            ]);
//        }
        $commission->delete();
        flash('تم حذف العموله بنجاح')->success();
        return  redirect()->route('MyCommission');
    }
}
