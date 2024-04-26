<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\ProviderRate;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderRateController extends Controller
{
    public function index($id)
    {
        $provider = Provider::findOrFail($id);
        $rates = $provider->rates()->paginate(200);
        return view('admin.providers.rates.index', compact('provider', 'rates'));
    }

    public function create($id)
    {
        $provider = Provider::findOrFail($id);
        $users = User::all();
        return view('admin.providers.rates.create', compact('provider', 'users'));
    }

    public function store(Request $request, $id)
    {
        $provider = Provider::findOrFail($id);
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'rate' => 'required|in:1,2,3,4,5',
            'rate_text' => 'nullable|string'
        ]);
        // create rate
        ProviderRate::updateOrCreate([
            'user_id' => $request->user_id,
            'provider_id' => $provider->id,
        ] , [
            'rate'         => $request->rate,
            'rate_text'    => $request->rate_text,
        ]);
        $provider->update([
            'rate' => providerRateAvg($provider->id)
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('showProviderRates', $provider->id);
    }
    public function edit($id)
    {
        $rate = ProviderRate::findOrFail($id);
        $users = User::all();
        return view('admin.providers.rates.edit', compact('rate', 'users'));
    }
    public function update(Request $request, $id)
    {
        $rate = ProviderRate::findOrFail($id);
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'rate' => 'required|in:1,2,3,4,5',
            'rate_text' => 'nullable|string'
        ]);
        // create rate
        $rate->update([
            'user_id'      => $request->user_id,
            'rate'         => $request->rate,
            'rate_text'    => $request->rate_text,
        ]);
        $rate->provider->update([
            'rate' => providerRateAvg($rate->provider->id)
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('showProviderRates', $rate->provider->id);
    }
    public function destroy($id)
    {
        $rate = ProviderRate::findOrFail($id);
        $rate->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('showProviderRates', $rate->provider->id);
    }

}
