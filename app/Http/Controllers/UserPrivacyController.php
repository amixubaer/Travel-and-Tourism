<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\Policy;

class UserPrivacyController extends Controller
{
    public function index(){
        $Policy = Policy::where('id', 1)->first();
        return view('user.privacy_policy')->with('policy', $Policy);
    }
}
