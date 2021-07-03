<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\AddHotelRoomRequest;
use App\Http\Requests\AddHotelFacilityRequest;
use App\Http\Requests\HotelSupportRequest;
use App\Http\Requests\HotelEditRoomRequest;
use App\Http\Requests\HotelEditFacilityRequest;
use App\Http\Requests\HotelProfileRequest;

use App\Room;
use App\Roombook;
use App\Facility;
use App\User;
use App\Review;
use App\Support;
use App\Hotel;
use App\Transaction;


class HotelController extends Controller
{
    public function index(Request $req){
        

        $roomCount = Room::all()->count();
        $roomBookCount = Roombook::all()->count();
        $reviewCount = Review::where('service_type', 'Hotel')->count();

        return view('hotelDashboard.home')->with('roomCount', $roomCount)
                                       ->with('roomBookCount', $roomBookCount)
                                       ->with('reviewCount', $reviewCount);
                            
    }

    public function addhotelfacility(){
        return view('hotelDashboard.addhotelfacility');
    }

    public function addhotelfacilityVerify(AddHotelFacilityRequest $req){

        if($req->hasFile('image')){

            $file = $req->file('image');
            echo "File Name: ".$file->getClientOriginalName()."<br>";
            echo "File Extension: ".$file->getClientOriginalExtension()."<br>";
            echo "File Mime Type: ".$file->getMimeType()."<br>";
            echo "File Size: ".$file->getSize()."<br>";

            if($file->move('upload','hotel'.$req->title.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
           $img='hotel'.$req->title.'.'.$file->getClientOriginalExtension();

            $facility = new Facility;
            $facility -> title = $req->title;
            $facility -> description = $req->description;
            $facility -> image = $img;
            $facility->save();
            return redirect()->route('hotel.addhotelfacility');
    }


    public function managehotelfacility(){

        $facilities = Facility::all();
        return view('hotelDashboard.managehotelfacility')->with('facilities', $facilities);
    }


    //hotel facility edit
    public function facilityedit($id){
        $facility = Facility::find($id);
         return view('hotelDashboard.edithotelfacility')->with('facility', $facility);
    }


    //hotel facility edit confirm
    public function facilityeditconfirm($id, HotelEditFacilityRequest $req){
        
        $facility = Facility::find($id);
        $facility->title = $req->title;
        $facility->description = $req->description;
        $facility->save();

        return redirect()->route('hotel.managehotelfacility');
    }

    //hotel facility delete 
    public function facilitydelete($id){
        $facility = Facility::find($id);
         return view('hotelDashboard.facilitydelete')->with('facility', $facility);
    }

    //hotel facility delete
    public function facilitydestroy($id){
        Facility::destroy($id);
        return redirect()->route('hotel.managehotelfacility');
    }

    public function addhotelroom(){
        return view('hotelDashboard.addhotelroom');
    }

    public function addhotelroomVerify(AddHotelRoomRequest $req){

        if($req->hasFile('image')){

            $file = $req->file('image');
            echo "File Name: ".$file->getClientOriginalName()."<br>";
            echo "File Extension: ".$file->getClientOriginalExtension()."<br>";
            echo "File Mime Type: ".$file->getMimeType()."<br>";
            echo "File Size: ".$file->getSize()."<br>";

            if($file->move('upload','hotel'.$req->name.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }

            $img='hotel'.$req->name.'.'.$file->getClientOriginalExtension();

            $room = new Room;
            $room ->hotel_id = session()->get('id');
            $room -> name = $req->name;
            $room -> price = $req->price;
            $room -> description = $req->description;
            $room -> availability = 'Available';
            $room -> image =$img;
            $room->save();
            return redirect()->route('hotel.addhotelroom');
    }

    public function managehotelroom(){
        $room = Room::all();
        return view('hotelDashboard.managehotelroom')->with('room', $room);
    }


    //hotel room edit
    public function roomedit($id){
        $room = Room::find($id);
         return view('hotelDashboard.edithotelroom')->with('room', $room);
    }


    //hotel room edit confirm
    public function roomeditconfirm($id, HotelEditRoomRequest $req){
        
        $room = Room::find($id);
        $room->name = $req->name;
        $room->description = $req->description;
        $room->price = $req->price;
        $room->availability = $req->availability;
        $room->save();

        return redirect()->route('hotel.managehotelroom');
    }

     //hotel room delete 
     public function roomdelete($id){
        $room = Room::find($id);
         return view('hotelDashboard.roomdelete')->with('room', $room);
    }

    //hotel room delete
    public function roomdestroy($id){
        Room::destroy($id);
        return redirect()->route('hotel.managehotelroom');
    }

    
    //hotel booking pending list
    public function ADroomBookList(){

            $roombooks = Roombook::where('req', 'Pending')->get();
            return view('hotelDashboard.ADroomBookList')->with('ADroomBookList', $roombooks);
    }
    //hotel booking pending approve list
    public function bookingapprove($id){
            $roombooks = Roombook::find($id);
            return view('hotelDashboard.bookingapprove')->with('roombooks', $roombooks);
    }
    
    //hotel booking confirmation request
    public function bookingadd($id){
            $roombook = Roombook::find($id);
            $roombook->req = 'Approved';
            $roombook->save();
            return redirect()->route('hotel.ADroomBookList');
    }
    
    //hotel booking pending decline list
     public function bookingdecline($id){
            $roombooks = Roombook::find($id);
             return view('hotelDashboard.bookingdecline')->with('roombooks', $roombooks);
    }

    //hotel booking remove request
    public function bookingremove($id){
        $roombook = Roombook::find($id);
        $roombook->req = 'Declined';
        $roombook->save();
        return redirect()->route('hotel.ADroomBookList');
    }


    //hotel booking delete from main list
    public function bookingdelete($id){
        $roombooks = Roombook::find($id);
         return view('hotelDashboard.bookingdelete')->with('roombooks', $roombooks);
    }
 

     //hotel booking delete request from main list
     public function bookingdestroy($id){
        $roombook = Roombook::find($id);
        $roombook->req = 'Canceled';
        $roombook->save();
        return redirect()->route('hotel.showhotelallboking');
    }


    //hotel booking list
    public function showhotelallboking(){
            $roombooks = Roombook::where('req', 'Approved')->get();
            return view('hotelDashboard.showhotelallboking')->with('showhotelallboking', $roombooks);
    }
    
    //hotel booking user-car information
    public function showcustomerroominfo($id){
        $roombook = Roombook::find($id); 
        $room = Room:: where('id', $roombook->room_id)->first();
        $user = User::where('id', $roombook->user_id)->first();
        
        return view('hotelDashboard.showcustomerroominfo')
        ->with('room', $room)
        ->with('user', $user);
                                                       
    }

    public function checkhotelreview(){
        $reviews = Review::where('service_id', session()->get('id'))
                       ->where('service_type', session()->get('type'))->get();

        return view('hotelDashboard.checkhotelreview')->with('reviews', $reviews);
    }

    public function hoteltransactionhistory(){
        $transactions = Transaction::where('receiver_id', session()->get('id'))
        ->where('receiver', session()->get('type'))->get();

        return view('hotelDashboard.hoteltransactionhistory')->with('transactions', $transactions);
    }

    public function hotelsupport(){
        return view('hotelDashboard.hotelsupport');
    }

    public function hotelsupportconfirm(HotelSupportRequest $req){

        $support = new Support;
        $support -> username = $req->username;
        $support -> phone = $req->phone; 
        $support -> email = $req->email;
        $support -> message = $req->message;
        $support->save();
        return redirect()->route('hotel.hotelsupport');

    }

    public function profile(Request $req){
        $id = session()->get('id');
        $profile = Hotel::find($id);
        return view('hotelDashboard.profile')->with('profile', $profile);
    }

    public function profileUD(HotelProfileRequest $req){

        switch ($req->input('submit')) {
            case 'Update':
                
                $hotel = Hotel::where('name', $req->name)->first();
                $hotel -> name = $req->name;
                $hotel -> location = $req->location;
                $hotel -> address = $req->address;
                $hotel -> phone = $req->phone;
                $hotel -> email = $req->email;
                $hotel -> password = $req->password;
                $hotel -> save();
                $req->session()->flash('hotelUDMsg', 'Account Updated');
                return redirect()->route('hotel.profile');
                break;

            case 'Delete':

                $transport = Transport::where('name', $req->name)->first();
                $transport->delete();
                return redirect()->route('login.index');
                break;

        }
    }
}