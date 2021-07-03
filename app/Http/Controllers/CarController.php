<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCarRequest;
use App\Http\Requests\CarEditRequest;
use App\Http\Requests\CarTypeRequest;
use App\Http\Requests\CarSupportRequest;
use App\Http\Requests\CarProfileRequest;

use Illuminate\Http\Request;
use App\Car;
use App\Carbook;
use App\User;
use App\Support;
use App\Transport;
use App\Review;
use App\Transaction;


class CarController extends Controller
{
    public function index(Request $req){
        
        $carCount = Car::all()->count();
        $carBookCount = Carbook::all()->count();
        $reviewCount = Review::where('service_type', 'Car')->count();

        return view('carDashboard.home')->with('carCount', $carCount)
                                       ->with('carBookCount', $carBookCount)
                                       ->with('reviewCount', $reviewCount);
                            
    }

    public function managecar(){
        $car = Car::all();
        return view('carDashboard.managecar')->with('car', $car);
    }

    public function addcar(){
        return view('carDashboard.addcar');
    }

    public function addcarVerify(AddCarRequest $req){

        if($req->hasFile('image')){

            $file = $req->file('image');
            echo "File Name: ".$file->getClientOriginalName()."<br>";
            echo "File Extension: ".$file->getClientOriginalExtension()."<br>";
            echo "File Mime Type: ".$file->getMimeType()."<br>";
            echo "File Size: ".$file->getSize()."<br>";

            if($file->move('upload','car'.$req->title.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
            $img='car'.$req->title.'.'.$file->getClientOriginalExtension();

            $car = new Car;
            $car -> title = $req->title;
            $car -> model = $req->model; 
            $car -> driver = $req->driver;
            $car -> type = $req->type;
            $car -> fare = $req->fare;
            $car -> availability = 'Available';
            $car -> image = $img;
            $car->save();
            return redirect()->route('car.addcar');

    }

    //car edit 
    public function caredit($id){
         $car = Car::find($id);
         return view('carDashboard.editcar')->with('car', $car);
    }

    
    //car edit confirm
    public function careditconfirm($id, CarEditRequest $req){
        
        $car = Car::find($id);
        $car->title = $req->title;
        $car->model = $req->model;
        $car->driver = $req->driver;
        $car->save();

        return redirect()->route('car.managecar');
    }

    //car delete confirmation
    public function cardelete($id){
        $car = Car::find($id);
         return view('carDashboard.cardelete')->with('car', $car);
    }

    //car delete
    public function cardestroy($id){
        Car::destroy($id);
        return redirect()->route('car.managecar');
    }

    //car type
    public function cartype(){

        $cars = Car:: all();
        return view('carDashboard.cartype')->with('cars', $cars);
    }

    //car type confirmation
    public function cartypeconfirm(CarTypeRequest $req ){
        
        $car = Car::where('title', $req->title)->first();
        $car->title = $req->title;
        $car->type = $req->type;
        $car->fare = $req->fare;
        $car->save();
        return redirect()->route('car.cartype');

    }

    //car availability
    public function caravailability(){
        
        $cars = Car:: all();
        return view('carDashboard.caravailability')->with('cars', $cars);;
    }


    //car availability confirm
    public function caravailabilityconfirm(Request $req ){
        
        $car = Car::where('title', $req->title)->first();
        $car->title = $req->title;
        $car->availability = $req->availability;
        $car->save();
        return redirect()->route('car.caravailability');

    }

    //car booking pending list
    public function ADcarBookList(){

        $carbook = Carbook::where('req', 'Pending')->get();
        return view('carDashboard.ADcarBookList')->with('ADcarBookList', $carbook);
    }

    //car booking pending approve list
    public function bookingapprove($id){
        $carbook = Carbook::find($id);
        return view('carDashboard.bookingapprove')->with('carbook', $carbook);
    }

    //car booking confirmation request
    public function bookingadd($id){
        $carbook = Carbook::find($id);
        $carbook->req = 'Approved';
        $carbook->save();
        return redirect()->route('car.ADcarBookList');
    }

    //car bookingpending decline list
    public function bookingdecline($id){
        $carbook = Carbook::find($id);
         return view('carDashboard.bookingdecline')->with('carbook', $carbook);
    }

    //car booking remove request
    public function bookingremove($id){
        $carbook = Carbook::find($id);
        $carbook->req = 'Declined';
        $carbook->save();
        return redirect()->route('car.ADcarBookList');
    }

    //car booking delete from main list
    public function bookingdelete($id){
        $carbook = Carbook::find($id);
         return view('carDashboard.bookingdelete')->with('carbook', $carbook);
    }

    //car booking delete request from main list
    public function bookingdestroy($id){
        $carbook = Carbook::find($id);
        $carbook->req = 'Canceled';
        $carbook->save();
        return redirect()->route('car.showcarallbooking');
    }

    //car booking list
    public function showcarallbooking(){
        $carbook = Carbook::where('req', 'Approved')->get();
        return view('carDashboard.showcarallbooking')->with('showcarallbooking', $carbook);
    }

    //car booking user-car information
    public function showusercarinfo($id){
        $carbook = Carbook::find($id); 
        $car = car:: where('id', $carbook->car_id)->first();
        $user = User::where('id', $carbook->user_id)->first();
        
        return view('carDashboard.showusercarinfo')
                                      ->with('car', $car)
                                      ->with('user', $user);                                            
    }


    public function checkcarreview(Request $request){
        $reviews = Review::where('service_id', session()->get('id'))
        ->where('service_type', session()->get('type'))->get();

        return view('carDashboard.checkcarreview')->with('reviews', $reviews);
    }

    public function cartransactionhistory(){
        $transactions = Transaction::where('receiver_id', session()->get('id'))
        ->where('receiver', session()->get('type'))->get();

        return view('carDashboard.cartransactionhistory')->with('transactions', $transactions);;
    }
    public function carsupport(){
        return view('carDashboard.carsupport');
    }

    public function carsupportconfirm(CarSupportRequest $req){

        $support = new Support;
        $support -> username = $req->username;
        $support -> phone = $req->phone; 
        $support -> email = $req->email;
        $support -> message = $req->message;
        $support->save();
        return redirect()->route('car.carsupport');

    }

    public function profile(Request $req){
        $id = session()->get('id');
        $profile = Transport::find($id);
        return view('carDashboard.profile')->with('profile', $profile);
    }

    public function profileUD(CarProfileRequest $req){

        switch ($req->input('submit')) {
            case 'Update':
                
                $transport = Transport::where('name', $req->name)->first();
                $transport -> name = $req->name;
                $transport -> phone = $req->phone;
                $transport -> email = $req->email;
                $transport -> password = $req->password;
                $transport -> save();
                $req->session()->flash('transportUDMsg', 'Account Updated');
                return redirect()->route('car.profile');
                break;

            case 'Delete':

                $transport = Transport::where('name', $req->name)->first();
                $transport->delete();
                return redirect()->route('login.index');
                break;

        }
    }
}
