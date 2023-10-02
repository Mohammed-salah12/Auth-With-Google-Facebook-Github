<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use function Symfony\Component\String\u;

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


    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        $user= Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }


    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback(){
        $user= Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);
       return redirect()->route('home');
    }
    public function redirectToGithub(){
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback(){
        $user= Socialite::driver('github')->user();

        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }



      protected function _registerOrLoginUser($data){
     $user=User::where('email','=',$data->email)->first();
     if(! $user){
         $user= new User();
         $user->name=$data->name;
         $user->email=$data->email;
         $user->provider_id=$data->provider_id;
         $user->avatar=$data->avatar;
         $user->save();
         Auth::login($user);
     }
      }



}
