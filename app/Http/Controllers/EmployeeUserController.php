<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class EmployeeUserController extends Controller
{
    public function userlist(){
        $users = User::all();
        return view('employee.userList')->with('users', $users);
    }
    public function userDetails($id){
        $users = User::find($id);
        return view('employee.userDetails')->with('users', $users);
    }

}
