<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getUserLocation()
    {

        //value of $address latitude and longitude is -7.0051453 and 110.4381254
        $address =[
            'latitude' => '-7.0051453',
            'longitude' => '110.4381254'
        ]
        ;
        return view('user-location',compact('address'));
    }
}
