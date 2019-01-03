<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
        //return Socialite::driver('facebook')->redirect(); //simple login
        return Socialite::driver('facebook')->scopes(
            [
                "manage_pages",
                "publish_pages",
                "pages_show_list"
            ])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {
        //$user = Socialite::driver('facebook')->user(); // Fetch authenticated user
        //dd($user);


        $auth_user = Socialite::driver('facebook')->user();

        $user = User::updateOrCreate(
            [
                'email' => $auth_user->email
            ],
            [
                'token' => $auth_user->token,
                'name'  =>  $auth_user->name
            ]
        );

        //echo $auth_user->token;
        //exit;
        Auth::login($user, true);

        return Redirect::to('/home');
        //$this->goBack($user);
    }

    public function goBack($user){

        //echo "$user->token";
        //return redirect()->to('/home'); // Redirect to a secure page

        return Redirect::to('/home');
    }
}
