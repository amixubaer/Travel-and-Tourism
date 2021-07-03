<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\About;

class UserAboutController extends Controller
{
    public function index(){
        $about = About::where('id', 1)->first();
        return view('user.about')->with('about', $about);
    }
    
}
