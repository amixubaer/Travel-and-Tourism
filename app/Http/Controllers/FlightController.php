<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFlightRequest;
use App\Http\Requests\FlightEditRequest;
use App\Http\Requests\FlightTypeRequest;
use App\Http\Requests\FlightSupportRequest;
use App\Http\Requests\FlightProfileRequest;

use Illuminate\Http\Request;
use App\Air;
use App\Airbook;
use App\User;
use App\Support;
use App\Transport;
use App\Review;
use App\Transaction;


class FlightController extends Controller
{
    public function index(Request $req){
        

        $airCount = Air::all()->count();
        $airBookCount = Airbook::all()->count();
        $reviewCount = Review::where('service_type', 'Flight')->count();

        return view('flightDashboard.home')->with('airCount', $airCount)
                                       ->with('airBookCount', $airBookCount)
                                       ->with('reviewCount', $reviewCount);
                                

        
    }

    public function addflight(){
        return view('flightDashboard.addflight');
    }

    public function addflightVerify(AddFlightRequest $req){

        if($req->hasFile('image')){

            $file = $req->file('image');
            echo "File Name: ".$file->getClientOriginalName()."<br>";
            echo "File Extension: ".$file->getClientOriginalExtension()."<br>";
            echo "File Mime Type: ".$file->getMimeType()."<br>";
            echo "File Size: ".$file->getSize()."<br>";

            if($file->move('upload','flight'.$req->title.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }

            $img='flight'.$req->title.'.'.$file->getClientOriginalExtension();

            $air = new Air;
            $air -> title = $req->title;
            $air -> model = $req->model;
            $air -> type = $req->type;
            $air -> fare = $req->fare;
            $air -> availability = 'Available';
            $air -> image = $img;
            $air->save();
            return redirect()->route('flight.addflight');

    }

    //manage flight
    public function manageflight(){
        $air = Air::all();
        return view('flightDashboard.manageflight')->with('air', $air);;
    }


    //flight edit
    public function flightedit($id){
        $air = Air::find($id);
        return view('flightDashboard.editflight')->with('air', $air);
   }

   
   //flight edit confirm
   public function flighteditconfirm($id, FlightEditRequest $req){
       
       $air = Air::find($id);
       $air->title = $req->title;
       $air->model = $req->model;
       $air->save();

       return redirect()->route('flight.manageflight');
   }

   //flight delete confirmation
   public function flightdelete($id){
    $air = Air::find($id);
     return view('flightDashboard.flightdelete')->with('air', $air);
}

    //flight delete
    public function flightdestroy($id){
        Air::destroy($id);
        return redirect()->route('flight.manageflight');
    }

    //flight type
    public function flighttype(){

        $air = Air:: all();
        return view('flightDashboard.flighttype')->with('air', $air);
    }

    //flight type confirmation
    public function flighttypeconfirm(FlightTypeRequest $req ){
        
        $air = Air::where('title', $req->title)->first();
        $air->title = $req->title;
        $air->type = $req->type;
        $air->fare = $req->fare;
        $air->save();
        return redirect()->route('flight.flighttype');

    }

     //flight availability
     public function flightavailability(){
        
        $air = Air:: all();
        return view('flightDashboard.flightavailability')->with('air', $air);;
    }

    //flight availability confirm
    public function flightavailabilityconfirm(Request $req ){
        
        $air = Air::where('title', $req->title)->first();
        $air->title = $req->title;
        $air->availability = $req->availability;
        $air->save();
        return redirect()->route('flight.flightavailability');

    }

    //flight booking pending list
    public function ADflightBookList(){

        $flightbook = Airbook::where('req', 'Pending')->get();
        return view('flightDashboard.ADflightBookList')->with('ADflightBookList', $flightbook);
    }

    //flight booking pending approve list
    public function bookingapprove($id){
        $flightbook = Airbook::find($id);
        return view('flightDashboard.bookingapprove')->with('flightbook', $flightbook);
    }   

    //flight booking confirmation request
    public function bookingadd($id){
        $flightbook = Airbook::find($id);
        $flightbook->req = 'Approved';
        $flightbook->save();
        return redirect()->route('flight.ADflightBookList');
    }

    //flight booking pending decline list
    public function bookingdecline($id){
        $flightbook = Airbook::find($id);
         return view('flightDashboard.bookingdecline')->with('flightbook', $flightbook);
    }

     //flight booking remove request
    public function bookingremove($id){
        $flightbook = Airbook::find($id);
        $flightbook->req = 'Declined';
        $flightbook->save();
        return redirect()->route('flight.ADflightBookList');
    }

    //flight booking delete from main list
    public function bookingdelete($id){
        $flightbook = Airbook::find($id);
         return view('flightDashboard.bookingdelete')->with('flightbook', $flightbook);
    }
 
    //flight booking delete request from main list
     public function bookingdestroy($id){
        $flightbook = Airbook::find($id);
        $flightbook->req = 'Canceled';
        $flightbook->save();
        return redirect()->route('flight.showflightallbooking');
    }

    //flight booking list
    public function showflightallbooking(){
        $flightbook = Airbook::where('req', 'Approved')->get();
        return view('flightDashboard.showflightallbooking')->with('showflightallbooking', $flightbook);
    }

    //flight booking user-air information
    public function showuserflightinfo($id){
        $flightbook = Airbook::find($id); 
        $air = air:: where('id', $flightbook->air_id)->first();
        $user = User::where('id', $flightbook->user_id)->first();
        
        return view('flightDashboard.showuserflightinfo')
                                      ->with('air', $air)
                                      ->with('user', $user);                                            
    }

    public function checkflightreview(){
        $reviews = Review::where('service_id', session()->get('id'))
        ->where('service_type', session()->get('type'))->get();

        return view('flightDashboard.checkflightreview')->with('reviews', $reviews);
    }

    public function flighttransactionhistory(){
        $transactions = Transaction::where('receiver_id', session()->get('id'))
        ->where('receiver', session()->get('type'))->get();

        return view('flightDashboard.flighttransactionhistory')->with('transactions', $transactions);;
    }

    public function profile(Request $req){
        $id = session()->get('id');
        $profile = Transport::find($id);
        return view('flightDashboard.profile')->with('profile', $profile);
    }

    public function profileUD(FlightProfileRequest $req){

        switch ($req->input('submit')) {
            case 'Update':
                
                $transport = Transport::where('name', $req->name)->first();
                $transport -> name = $req->name;
                $transport -> phone = $req->phone;
                $transport -> email = $req->email;
                $transport -> password = $req->password;
                $transport -> save();
                $req->session()->flash('transportUDMsg', 'Account Updated');
                return redirect()->route('flight.profile');
                break;

            case 'Delete':

                $transport = Transport::where('name', $req->name)->first();
                $transport->delete();
                return redirect()->route('login.index');
                break;

        }
    }

    public function flightsupport(){
        return view('flightDashboard.flightsupport');
    }

    public function flightsupportconfirm(FlightSupportRequest $req){

        $support = new Support;
        $support -> username = $req->username;
        $support -> phone = $req->phone; 
        $support -> email = $req->email;
        $support -> message = $req->message;
        $support->save();
        return redirect()->route('flight.flightsupport');

    }
}
