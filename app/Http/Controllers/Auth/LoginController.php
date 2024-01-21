<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))

        {
            if(auth()->user()->is_admin == 1)
            {

                $user = User::find(auth()->user()->id);
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $user->latitude = $latitude;
                $user->longitude = $longitude;
                $user->save();
                return redirect()->route('home');
                

            }
            if(auth()->user()->is_admin == 0)
            {
                $user = User::find(auth()->user()->id);
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $user->latitude = $latitude;
                $user->longitude = $longitude;
                $user->save();
                return redirect()->route('sales.home');
            }
            
            else{
                //return error redirect back
                return redirect()->back()->with('error','Email-Address And Password Are Wrong or Location not found.');

            }
        }else{
            return redirect()->back()->with('error','Email-Address And Password Are Wrong or Location not found.');
        }
    }

    public function LoginForm()
    {
        return view('auth.login');
    }
}
