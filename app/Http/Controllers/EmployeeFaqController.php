<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpFaqRequest;
Use App\Faq;
class EmployeeFaqController extends Controller
{
    public function faq(){
        $faqs = Faq::all();
        return view('employee.faq')->with('faqs',$faqs);
    }

    public function faqAdd(EmpFaqRequest $req){
       $faq = new Faq;
       $faq -> que = $req->que;
       $faq -> ans = $req->ans;
       $faq -> save();
       return redirect()->route('employee.faq');
        
    }

    public function faqDelete($id){
        
        $faq = Faq::find($id);
         return view('employee.faqDelete')->with('faq', $faq);
    }
    
    public function faqDestroy($id){
        Faq::destroy($id);
        return redirect()->route('employee.faq');
    }
    
}
 