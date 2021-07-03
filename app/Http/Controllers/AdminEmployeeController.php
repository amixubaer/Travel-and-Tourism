<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddRequest;
Use Exception;
use App\Employee;

class AdminEmployeeController extends Controller
{
    public function employeeAdd(){
        return view('admin.empAdd');
    }

    public function employeeAddVerify(AddRequest $req){

        $employees = Employee::all();
        foreach ($employees as $e)
        {
            if($e->username==$req->username || $e->email==$req->email)
            {
                $req->session()->flash('empAddMsg', 'Duplicate email or username');
                return redirect()->route('adminEmployee.employeeAdd');
            }
        }
        
        
        $employee = new Employee;
        $employee -> firstname = $req->firstname;
        $employee -> lastname = $req->lastname;
        $employee -> gender = $req->gender;
        $employee -> email = $req->email;
        $employee -> username = $req->username;
        $employee -> password = $req->password;
        $employee -> status ='Active';

        try
        {
            if(count((array)$employee -> save()))
            {
                $req->session()->flash('empAddMsg', 'Employee added');
                return redirect()->route('adminEmployee.employeeAdd');
            }
        }
        catch(Exception $e)
        {
            dd($e->getMessage());
        }     
        
    }

    public function employeeList(){
        $employees = Employee::all();
        return view('admin.empList')->with('allEmployeeList', $employees);
    }

    public function activeEmployeeList(){
        $employees = Employee::where('status', 'Active')->get();;
        return view('admin.activeEmpList')->with('activeEmployeeList', $employees);
    }

    public function employeeDetails($id){
        $employee = Employee::find($id);
        return view('admin.employeeDetails')->with('employee', $employee);
    }

    public function employeeDelete($id){
        $employee = Employee::find($id);
        return view('admin.empDelete')->with('employee', $employee);

    }

    public function employeeDestroy($id){
        Employee::destroy($id);
        return redirect()->route('adminEmployee.employeeList');
    }

}
