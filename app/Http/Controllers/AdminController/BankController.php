<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::orderBy('id' , 'desc')->get();
        return view('admin.banks.index' , compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banks.create');
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
            'name'           => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'IBAN_number'    => 'nullable|max:191',
        ]);
        // create new Bank
        Bank::create([
            'name'           => $request->name,
            'account_number' => $request->account_number,
            'IBAN_number'    => $request->IBAN_number,
        ]);
        flash('تم أضافه بيانات  البنك بنجاح')->success();
        return redirect()->route('Bank');
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
        $bank = Bank::findOrFail($id);
        return view('admin.banks.edit' , compact('bank'));
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
        $bank = Bank::findOrFail($id);
        $this->validate($request , [
            'name'           => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'IBAN_number'    => 'nullable|max:191',
        ]);
        $bank->update([
            'name'           => $request->name,
            'account_number' => $request->account_number,
            'IBAN_number'    => $request->IBAN_number == null ? $bank->IBAN_number : $request->IBAN_number,
        ]);
        flash('تم تعديل بيانات  البنك بنجاح')->success();
        return redirect()->route('Bank');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        flash('تم تعديل بيانات  البنك بنجاح')->success();
        return redirect()->route('Bank');
    }
}
