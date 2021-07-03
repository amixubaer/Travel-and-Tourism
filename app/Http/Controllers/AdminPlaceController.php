<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Place;

class AdminPlaceController extends Controller
{
    public function adPlace(){
        $places = Place::where('req', 'Pending')->get();
        return view('admin.ADPlace')->with('ADPlaceList', $places);
    }

    public function placeApprove($id){
        $place = Place::find($id);
        return view('admin.placeApprove')->with('place', $place);
    }

    public function placeAdd($id){
        $place = Place::find($id);
        $place->req = 'Approved';
        $place->save();

        return redirect()->route('AdminPlace.adPlace');
    }

    public function placeDecline($id){
        $place = Place::find($id);
        return view('admin.placeDecline')->with('place', $place);
    }

    public function placeRemove($id){
        Place::destroy($id);
        return redirect()->route('AdminPlace.adPlace');
    }


    public function placeList(){
        $places = Place::where('req', 'Approved')->get();
        return view('admin.placeList')->with('placeList', $places);
    }

    public function placeDelete($id){
        $place = Place::find($id);
        return view('admin.placeDelete')->with('place', $place);

    }

    public function placeDestroy($id){
        Place::destroy($id);
        return redirect()->route('AdminPlace.placeList');
    }

}
