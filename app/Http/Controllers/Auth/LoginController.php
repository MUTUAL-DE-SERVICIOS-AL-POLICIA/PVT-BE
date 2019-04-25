<?php

namespace Muserpol\Http\Controllers\Auth;

use Muserpol\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Muserpol\User;
use Auth;
use Ldap;
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

  public function login()
  {
    $request = request()->all();

    if (!env("LDAP_AUTHENTICATION") || $request['username'] == 'admin') {
      if (Auth::attempt(['username' => $request['username'], 'password' => $request['password']])) {
        session(['username' => $request['username']]);
      } else {
        Session::flash('message', 'Usuario o contraseña incorrectos');
      }
    } else {
      $ldap = new Ldap();

      if ($ldap->connection && $ldap->verify_open_port()) {
        if ($ldap->bind($request['username'], $request['password'])) {
          $user = User::whereUsername($request['username'])->whereStatus('active')->first();
          if ($user) {
            if (!Hash::check($request['password'], $user->password)) {
              $user->password = Hash::make($request['password']);
              $user->save();
            }
            $ldap->unbind();

            Auth::attempt(['username' => $request['username'], 'password' => $request['password']]);
            session(['username' => $request['username']]);
          } else {
            Session::flash('message', 'Usuario o contraseña incorrectos');
          }
        }
      }
    }

    return redirect('/');
  }

  public function logout()
  {
    //logout user
    auth()->logout();
    // redirect to homepage
    Log::info('Cyk');
    if (Session::has('rol_id'))
      Session::forget('rol_id');
    if (Session::has('rol_name'))
      Session::forget('rol_name');

    Log::info('Olvidando a la session ');

    return redirect('/');
  }
}
