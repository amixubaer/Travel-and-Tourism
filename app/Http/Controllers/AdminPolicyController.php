<?php

namespace App\Http\Controllers;

use App\Http\Requests\pRequest;
use Illuminate\Http\Request;
use App\Policy;

class AdminPolicyController extends Controller
{
    public function index(){
        $policy = Policy::where('id', 1)->first();
        return view('admin.policy')->with('policy', $policy);
    }

    public function form($id){

        $policy = Policy::find($id);
        return view('admin.policyForm')->with('policy', $policy);
    }

    public function edit(pRequest $req, $id){

        $policy = Policy::find($id);
        $policy-> policy = $req->policyForm;
        $policy->save();
        return view('admin.policy')->with('policy', $policy);
    }
}
