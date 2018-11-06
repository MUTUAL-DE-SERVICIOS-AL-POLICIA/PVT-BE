<?php

namespace Muserpol\Http\Controllers\Auth;

use Muserpol\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Log;
use Session;

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
    protected $redirectTo = '/changerol';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    
    public function logout () {
        //logout user
        auth()->logout();
        // redirect to homepage
        Log::info('Cyk');
        if(Session::has('rol_id'))
            Session::forget('rol_id');
        if(Session::has('rol_name'))
            Session::forget('rol_name');

         Log::info('Olvidando a la session ');

        return redirect('/');
    }
   
}
