<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpGuidelineRequest;
use App\Guideline;
class EmployeeGuidelinesController extends Controller
{
    public function index(){

        $guideline = Guideline::where('id', 1)->first();
        return view('employee.guideline')->with('guideline', $guideline);
    }

    public function form($id){

        $guideline = Guideline::find($id);
        return view('employee.guidelineForm')->with('guideline', $guideline);
    }

    public function edit(EmpGuidelineRequest $req, $id){

        $guideline = Guideline::find($id);
        $guideline-> guideline = $req->guidelineForm;
        $guideline->save();
        return view('employee.guideline')->with('guideline', $guideline);
    }

}
