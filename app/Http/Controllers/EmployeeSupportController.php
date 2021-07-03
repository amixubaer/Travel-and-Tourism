<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support;
class EmployeeSupportController extends Controller
{
    public function supportList(){
        $support = Support::all();
        return view('employee.supportList')->with('supports', $support);
    }

    public function supportDelete($id){
        
        $support = Support::find($id);
         return view('employee.supportDelete')->with('supports', $support);
    }
    
    public function supportDestroy($id){
        Support::destroy($id);
        return redirect()->route('employee.supportList');
    }
}
