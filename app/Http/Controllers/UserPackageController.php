<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Packagebook;

class UserPackageController extends Controller
{
    public function showPackage(){
        $packages = Package::where('req', 'Approved')
                             ->where('status', 'Upcoming')->get();
        return view('user.package')->with('ADPackageList', $packages);
    }

    public function packageBook($id){
        $package = Package::find($id);
        return view('user.packageBook')->with('package', $package);
    }

    public function confirmpackageBook($id){

        $package = Package::find($id);
        $packagebook = new Packagebook;
        
        $packagebook -> user_id = 1;
        $packagebook -> package_id = $package-> id;
        $packagebook -> place = $package-> place;
        $packagebook -> image = $package-> image;
        $packagebook -> status = 'Upcoming';
        $packagebook -> save();
        return redirect()->route('userPackage.showPackage');
    }
}
