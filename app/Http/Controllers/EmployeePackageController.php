<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpPackageRequest;
use App\Package;
class EmployeePackageController extends Controller
{
    public function package(){
        $packages = Package::where('req', 'Approved')->get();
        return view('employee.packageManage')->with('packages', $packages);
    }


    public function packageAdd(){
        return view('employee.packageAdd');
        
    }

    public function packageAdded(EmpPackageRequest $req){
        if ($req->hasFile('image')) {
            $file = $req->file('image');
            if($file->move('upload', 'employeePackage'.$req->id.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
        $img='employeePackage'.$req->id.'.'.$file->getClientOriginalExtension();

        $package = new Package;
        $package -> place = $req->place;
        $package -> location = $req->location;
        $package -> image = $img;
        $package -> description = $req->description;
        $package -> duration = $req->duration;
        $package -> transport = $req->transport;
        $package -> hotel = $req->hotel;
        $package -> cost = $req->cost;
        $package -> status = 'Upcoming';
        $package -> req = 'Pending';
        $package -> save();
        return redirect()->route('employeePackage.packageAdd');
        
    }

    public function packageEdit($id){
        $package = Package::find($id);
        return view('employee.packageEdit')->with('packages',  $package);

    }

    public function packageEdited($id, EmpPackageRequest $req){


                if ($req->hasFile('image')) {
                    $file = $req->file('image');
                    if($file->move('upload', 'employeePackage'.$req->id.'.'.$file->getClientOriginalExtension())){
                        echo "success";
                    }else{
                        echo "error";
                    }
                }
                $img='employeePackage'.$req->id.'.'.$file->getClientOriginalExtension();

                $package = Package::where('id', $id)->first();
                $package -> place = $req->place;
                $package -> location = $req->location;
                $package -> image = $img;
                $package -> description = $req->description;
                $package -> duration = $req->duration;
                $package -> transport = $req->transport;
                $package -> hotel = $req->hotel;
                $package -> cost = $req->cost;
                $package -> status = $req->status;
                $package -> req = 'Approved';
                $package -> save();
                $req->session()->flash('packageUDMsg', 'Package Updated');
                return redirect()->route('employeePackage.package');
    }

    public function packageDelete($id){
        $package = Package::find($id);
        $package ->delete();
        return redirect()->route('employeePackage.package');
    }


}
