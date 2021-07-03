<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\Guideline;

class UserGuideLineController extends Controller
{
    public function showGuideline(){
        $Guidelines = Guideline::where('id', 1)->first();
        return view('user.user_guideline')->with('guideline', $Guidelines);
    }
}
