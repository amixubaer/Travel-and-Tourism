<?php

namespace App\Http\Controllers;

use App\Http\Requests\gRequest;
use Illuminate\Http\Request;
use App\Guideline;

class AdminGuidelinesController extends Controller
{
    public function index(){

        $guideline = Guideline::where('id', 1)->first();
        return view('admin.guideline')->with('guideline', $guideline);
    }

    public function form($id){

        $guideline = Guideline::find($id);
        return view('admin.guidelineForm')->with('guideline', $guideline);
    }

    public function edit(gRequest $req, $id){

        $guideline = Guideline::find($id);
        $guideline-> guideline = $req->guidelineForm;
        $guideline->save();
        return view('admin.guideline')->with('guideline', $guideline);
    }
}
