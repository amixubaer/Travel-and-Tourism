<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\Roombook;

class UserHotelController extends Controller
{
    public function index(){
        $hotelbookings = Roombook::where('req', 'Approved')->get();
        return view('user.hotel_booking')->with('HotelBookingsList', $hotelbookings);
    }

}
