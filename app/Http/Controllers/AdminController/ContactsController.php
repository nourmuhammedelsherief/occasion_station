<?php

namespace App\Http\Controllers\AdminController;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notifications\replay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = ContactUs::orderBy('id' , 'desc')
            ->whereArchived('false')
            ->paginate(100);
        return view('admin.contacts.index' , compact('contacts'));
    }


    public function ArchivedContact()
    {
        $contacts = ContactUs::orderBy('id' , 'desc')
            ->whereArchived('true')
            ->paginate(100);
        return view('admin.contacts.archived' , compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request)
    {
        // $user->notify (new replay($request->msg_body));
//        Mail::to($company->email);
        $email = $request->receiver_email;
        $contact = ContactUs::find($request->id);
        $contact->update([
            'reply' => $request->msg_body
        ]);
        Mail::send('emails.contact', ['contact' => $contact], function ($m) use ($contact) {
            $m->from('0ccasion@0ccasion.station.tqnee.com', '0ccasion_station');

            $m->to($contact->email)->subject('رد الأدراه علي رسالتك !');
        });
//        $data = [
//            'contact'          => $contact,
//        ];
//        Mail::to($contact->email)->send(new \App\Mail\ReplyMessage($data));

        $v = '{"message":"done"}';
        return response()->json($v);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactU = ContactUs::findOrFail($id);
        return view('admin.contacts.show' , compact('contactU'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();
        flash('تم المسح  بنجاح')->success();
        if (auth()->guard('admin')->user()->admin_category_id == 4)
        {
            return redirect()->route('Contact');
        }else{
            return redirect()->route('ContactE');
        }
    }

    public function archived($id , $status)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->update([
            'archived' => $status,
        ]);
        flash('تمت التعديل بنجاح')->success();
        if (auth()->guard('admin')->user()->admin_category_id == 4)
        {
            return redirect()->route('Contact');
        }else{
            return redirect()->route('ContactE');
        }
    }
}
