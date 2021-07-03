<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\Faq;

class UserFaqController extends Controller
{
    public function index(){
        $Faqs = Faq::all();
        return view('user.faq')->with('showFAQ', $Faqs);
    }
}
