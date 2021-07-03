<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpAdsRequest;
use App\Http\Requests\EmpPromoRequest;
use App\Promo;
use App\Ad;
class EmployeeAdvertisementController extends Controller
{
    public function adsIndex(){

        $ads = Ad::all();
        return view('employee.ads')->with('ads', $ads);
    }

    public function adsVerify(EmpAdsRequest $req){

        if ($req->hasFile('image')) {
            $file = $req->file('image');

            if($file->move('upload','employeeAds'.$file->getClientOriginalName().'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
        $img= 'employeeAds'. $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

        $ads = new Ad;
        $ads -> image = $img;
        $ads -> save();
        return redirect()->route('employee.adsIndex');
    }

    public function adsDelete($id){
        
        $ads = Ad::find($id);
         return view('employee.adsDelete')->with('ads', $ads);
    }
    
    public function adsDestroy($id){
        Ad::destroy($id);
        return redirect()->route('employee.adsIndex');
    }




    public function promoIndex(){
        
        $promos = Promo::all();
        return view('employee.SendPromo')->with('promos', $promos);
    }

    public function promotions(EmpPromoRequest $req){


        if ($req->hasFile('image')) {
            $file = $req->file('image');
            if($file->move('upload', 'employeePromos'.$req->id.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
        $img='employeePromos'.$req->id.'.'.$file->getClientOriginalExtension();


        $promos = new Promo;
        $promos -> image = $img;
        $promos -> message = $req->message;
        $promos -> save();
        return redirect()->route('employee.promoIndex');
        
    }

    public function promoDelete($id){
        
        $promos = Promo::find($id);
         return view('employee.promoDelete')->with('promos', $promos);
    }
    
    public function promoDestroy($id){
        Promo::destroy($id);
        return redirect()->route('employee.promoIndex');
    }

    
}
