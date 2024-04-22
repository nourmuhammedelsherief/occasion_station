<?php

namespace App\Http\Controllers\AdminController;

use App\Provider;
use App\ProviderCommissionHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
    public function provider_commissions($id)
    {
        $provider = Provider::findOrFail($id);
        $commissions = ProviderCommissionHistory::whereProviderId($provider->id)->get();
        return view('admin.commissions.index' , compact('provider' , 'commissions'));
    }

    public function confirm_commission($id)
    {
        $commission = ProviderCommissionHistory::findOrFail($id);
        // confirm the operation
        $commission->update([
            'status'  => 'done',
        ]);
        // reduce the commission from the provider commissions
        $provider_commission = $commission->provider->commission - $commission->amount;
        $commission->provider->update([
            'commission' => $provider_commission,
        ]);
        flash('تم عمليه التاكد من دفع العموله بنجاح')->success();
        return redirect()->back();
    }
    public function cancel_commission($id)
    {
        $commission = ProviderCommissionHistory::findOrFail($id);
        if ($commission->status == 'done')
        {
            // add the commission to the provider commissions
            $provider_commission = $commission->provider->commission + $commission->amount;
            $commission->provider->update([
                'commission' => $provider_commission,
            ]);
        }
        // cancel the operation
        $commission->update([
            'status'  => 'canceled',
        ]);
        flash('تم الغاء عمليه دفع العموله بنجاح')->success();
        return redirect()->back();
    }
}
