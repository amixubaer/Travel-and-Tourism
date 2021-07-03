<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserContactRequest;
use App\Support;

class UserContactController extends Controller
{
    public function index (){
        return view('user.contact');
    }

    public function verify (UserContactRequest $req){

        $contact = new Support;
        $contact -> username = $req->username;
        $contact -> phone = $req->phone;
        $contact -> email = $req->email;
        $contact -> message = $req->message;
        $contact->save();
        return redirect()->route('userContact.index');
        
    }


}
