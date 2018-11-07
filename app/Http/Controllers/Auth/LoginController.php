<?php

namespace Muserpol\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Log;
use Session;
use Ldap;
use Muserpol\User;

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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
    public function login(Request $request)
    {
            if (!env("LDAP_AUTHENTICATION")) {
                $this->validateLogin($request);
                if ($this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);
                    return $this->sendLockoutResponse($request);
                }
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            } 
            else {
                $ldap = new Ldap();
                if ($ldap->connection && $ldap->verify_open_port()) {
                    if ($ldap->bind($request['username'], $request['password'])) {
                        $ldap->unbind();
                        $user = User::where('username', $request['username'])->where('status', 'active')->first();
                        if ($user) {
                            $this->validateLogin($request);
                            if ($this->hasTooManyLoginAttempts($request)) {
                                $this->fireLockoutEvent($request);
                                return $this->sendLockoutResponse($request);
                            }
                            if ($this->attemptLogin($request)) {
                                return $this->sendLoginResponse($request);
                            }
                            $this->incrementLoginAttempts($request);
                            return $this->sendFailedLoginResponse($request);
                        }
                    }
                    return $this->sendFailedLoginResponse($request);
                }
                else{
                    return $this->sendFailedLoginResponse($request);
                }
            }
        
    }
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
