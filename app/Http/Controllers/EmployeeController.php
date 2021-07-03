<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpProfileRequest;
use App\Employee;
use App\User;
use App\Package;
use App\Place;
use App\Hotel;
use App\Transport;
use App\Support;
class EmployeeController extends Controller
{
    public function index(Request $req){
        if($req->session()->has('uname'))
        {
            
            $userCount = User::where('status', 'Active')->count();
            $packageCount = Package::where('req', 'Approved')->count();
            $placeCount = Place::where('req', 'Approved')->count();
            $hotelCount = Hotel::where('req', 'Approved')->count();
            $transportCount = Transport::where('req', 'Approved')->count();
            $supportCount = Support::all()->count();


            return view('employee.home')->with('userCount', $userCount)
                                    ->with('packageCount', $packageCount)
                                    ->with('placeCount', $placeCount)
                                    ->with('hotelCount', $hotelCount)
                                    ->with('transportCount', $transportCount)
                                    ->with('supportCount',  $supportCount);
        }
        else{
            $req->session()->flash('msg', 'Unauthorized request');
            return redirect('/login');
        }
      
    }

    public function profile(Request $req){
        $id = session()->get('id');
        $profile = Employee::find($id);
        return view('employee.profile')->with('profile', $profile);
    }

    public function profileUD(EmpProfileRequest $req){

        switch ($req->input('submit')) {
            case 'Update':
                
                $employee = Employee::where('username', $req->username)->first();
                $employee -> firstname = $req->firstname;
                $employee -> lastname = $req->lastname;
                $employee -> gender = $req->gender;
                $employee -> email = $req->email;
                $employee -> password = $req->password;
                $employee -> save();
                $req->session()->flash('employeeUDMsg', 'Account Updated');
                return redirect()->route('employee.profile');
        
                break;


    
            case 'Delete':

                $employee = Employee::where('username', $req->username)->first();
                $employee->delete();
                return redirect()->route('login.index');
                
                break;

        }
    
    }
    
}
