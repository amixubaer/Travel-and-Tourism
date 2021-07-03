<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Admin;
use App\Employee;
use App\User;
use App\Transport;
use App\Hotel;
use Socialite;


class LoginController extends Controller
{
    public function index(){
        return view('login.login');
    }

    public function verify(LoginRequest $req){
      
        
        $admin = Admin::where('username', $req->uname)
                    ->where('password', $req->password)
                    ->first();

        $employee = Employee::where('username', $req->uname)
                            ->where('password', $req->password)
                            ->first();

        $user = User::where('username', $req->uname)
                    ->where('password', $req->password)
                    ->first();

        $transport = Transport::where('name', $req->uname)
                                ->where('password', $req->password)
                                ->first();

        $hotel = Hotel::where('name', $req->uname)
                    ->where('password', $req->password)
                    ->first();

        if(count((array)$admin) > 0){
            $req->session()->put('uname', $admin->username);
            $req->session()->put('id', $admin->id);
            $req->session()->put('type', $admin->type);
            $req->session()->put('image', $admin->image);

            return redirect()->route('admin.index');
        }
        elseif(count((array)$employee) > 0){
            $req->session()->put('uname', $employee->username);
            $req->session()->put('id', $employee->id);
            $req->session()->put('type', $employee->type);
            
            return redirect()->route('employee.index');
        }

        elseif(count((array)$user) > 0){
            $req->session()->put('uname', $user->username);
            $req->session()->put('id', $user->id);
            $req->session()->put('type', $user->type);
            
            return redirect()->route('user.home');
        }

        elseif(count((array)$transport) > 0){
            $req->session()->put('uname', $transport->name);
            $req->session()->put('id', $transport->id);
            $req->session()->put('type', $transport->type);
            
            if($transport->type == 'Car')
            {
                return redirect()->route('car.index');
            }
            elseif($transport->type == 'Flight')
            {
                return redirect()->route('flight.index');
            }
            
        }

        elseif(count((array)$hotel) > 0){
            $req->session()->put('uname', $hotel->name);
            $req->session()->put('id', $hotel->id);
            $req->session()->put('image', $hotel->image);
            $req->session()->put('type', $hotel->type);
            
            return redirect()->route('hotel.index');
        }

        else{
            $req->session()->flash('msg', 'invaild username or password');
            return redirect()->route('login.index');
        }
        
    }



    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $req)
    {
        $google = Socialite::driver('google')->user();
        //dd($admin);

        $admin= Admin::firstOrCreate([
            'username'=> $google->getName(),
            'email'=> $google->getEmail(),
            'image'=> 'guest.jpg',

        ]);

        $req->session()->put('uname', $google->getName());
        $req->session()->put('type', 'Admin');
        $req->session()->put('image', 'guest.jpg');
        return redirect()->route('admin.index');

        // $user->token;
    }
}
