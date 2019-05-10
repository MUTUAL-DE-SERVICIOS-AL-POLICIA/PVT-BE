<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Muserpol\User;
use Auth;
use Session;
use Muserpol\Models\Role;
use Muserpol\Helpers\Util;
use Muserpol\Models\Module;
use Muserpol\Models\City;
use Ldap;

use Yajra\Datatables\Datatables;
use DB;
//use Muserpol\Http\Controllers\DataTables;
class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  //aca------------
  public function index()
  {
    // if(Session::has('rol_id'))
    // {

    return view('users.index');
    // }
    // else
    // {
    //     return redirect('changerol');
    // }
  }

  public function getUsersLdapUpdate()
  {
    $ldap = new Ldap();
    if ($ldap->connection && $ldap->verify_open_port()) {
      $ldap_users = $ldap->list_entries();
      $users = User::whereStatus('active')->get();
      foreach ($users as $user) {
        if ($user->username != 'admin' && $user->username != 'asistente') {
          $exists = array_filter($ldap_users, function ($o) use ($user) {
            if ($o->uid == $user->username) {
              return true;
            } else {
              return false;
            }
          });
          if (count($exists) == 0) {
            $user->status = 'inactive';
            $user->save();
          }
        }
      }
      $ldap->unbind();
    }
    return redirect('/user');
  }

  /**
   * Process datatables ajax request.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getUserDatatable()
  {
    $users = User::with('roles')->orderBy('last_name')->get();
    return Datatables::of($users)
      ->addColumn('action', function ($u) {
        return '<a href="/user/' . $u->id . '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Editar</a>';
      })
      ->editColumn('city_id', function ($u) {
        return $u->city->name ?? "";
      })
      ->editColumn('first_name', function ($u) {
        return $u->last_name ?? "" . ' ' . $u->first_name ?? "";
      })
      ->addColumn('button-roles', function ($u) {
        return '<a class="btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Ver</a>';
      })
      ->addColumn('state', function ($u) {
        return '<a href="/user/' . ($u->status == 'active' ? 'inactive' : 'active') . '/' . $u->id . '" class="btn btn-xs btn-primary"><i class="fa fa-arrow-circle-up"></i> Cambiar</a>';
      })->rawColumns(['state', 'action', 'button-roles'])
      ->make(true);
  }

  public function Data()
  {
    $users = User::select(['id', 'username', 'first_name', 'last_name', 'position', 'phone', 'status', 'city_id'])->where('id', '>', 1);
    return Datatables::of($users)
      ->addColumn('name', function ($user) {
        return Util::ucw($user->first_name) . ' ' . Util::ucw($user->last_name);
      })
      ->addColumn('module', function ($user) {
        return $user->roles()->first()->module()->first()->name;
      })
      ->addColumn('city', function ($user) {
        return $user->city()->first()->name;
      })
      ->addColumn('role', function ($user): string {
        $roles_list = [];
        foreach ($user->roles as $role) {
          $roles_list[] = $role->name;
        }
        return implode(",", $roles_list);
      })
      ->addColumn('status', function ($user) {
        return $user->status == 'active' ? 'Activo' : 'Inactivo';
      })
      ->addColumn('action', function ($user) {
        return $user->status == "active" ?
          '<div class="btn-group" style="margin:-3px 0;">
                    <a href="user/' . $user->id . '/edit "class="btn btn-primary btn-raised btn-sm">&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;</a>
                    <a href="" class="btn btn-primary btn-raised btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span>&nbsp;</a>
                    <ul class="dropdown-menu">
                        <li><a href="user/block/' . $user->id . ' " style="padding:3px 5px;"><i class="glyphicon glyphicon-ban-circle"></i> Bloquear</a></li>
                    </ul>
                </div>' : '<div class="btn-group" style="margin:-3px 0;">
                    <a href="user/' . $user->id . '/edit " class="btn btn-primary btn-raised btn-sm">&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;</a>
                    <a href="" class="btn btn-primary btn-raised btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span>&nbsp;</a>
                    <ul class="dropdown-menu">
                        <li><a href="user/unblock/' . $user->id . ' " style="padding:3px 5px;"><i class="glyphicon glyphicon-ok-circle"></i> Activar</a></li>
                    </ul>
                </div>';
      })->make(true);
  }
  //hasta aca---------

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $modules = Module::all();
    foreach ($modules as $module) {
      $module->icon = Util::IconModule($module->id);
    }
    $cities = City::where('first_shortened', '!=', "")->select('name', 'id')->orderBy('name')->get();
    $citiesT = City::all();
    $roles = Role::all();
    $citiesL = array('' => '');
    foreach ($citiesT as $city) {
      $citiesL[$city->id] = $city->name;
    }

    $ldap = new Ldap();
    if ($ldap->connection && $ldap->verify_open_port()) {
      $ldap_users = $ldap->list_entries();
      $users = User::whereStatus('active')->select('username')->get();
      foreach ($users as $user) {
        $ldap_users = array_filter($ldap_users, function ($obj) use ($user) {
          return $obj->uid != $user->username;
        });
      }
      $ldap->unbind();
    }

    $data = array(
      'users' => array_values($ldap_users),
      'modules' => $modules,
      'cities' => $cities,
      'roles' => $roles,
      'citiesL' => $citiesL,
    );

    return view('users.registro')->with($data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($request->has('user_id')) {
      $user = User::findOrFail($request->user_id);
      // return $user;
    } else {
      $user = new User;
      // dd($user);
    }
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->phone = $request->phone;
    $user->position = $request->position;
    $user->username = $request->username;
    $user->city_id = $request->city_id;
    if (!isset($user->id)) {
      $user->password = Hash::make($user->username);
    }
    if ($request->has('password') && $request->has('old_password')) {
      if (Hash::check($request['old_password'], $user->password)) {
        $user->password = Hash::make($request['password']);
      } else {
        return response()->json([
          'message' => 'Contraseña anterior incorrecta'
        ], 400);
      }
    }

    $user->save();

    $user->roles()->sync($request->rol);

    return redirect('user');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::findOrFail($id);
    $ldap = new Ldap();
    if ($ldap->connection && $ldap->verify_open_port()) {
      $ldap_user = $ldap->get_entry($user->username, 'uid');
      $ldap->unbind();
    }

    if ($ldap_user) {
      return response()->json($ldap_user);
    } else {
      abort(404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = User::findOrFail($id);
    $user->rol = $user->roles->pluck('id');
    $modules = Module::all();
    foreach ($modules as $module) {
      $module->icon = Util::IconModule($module->id);
    }
    $cities = City::where('first_shortened', '!=', "")->select('name', 'id')->orderBy('name')->get();
    $roles = Role::all();
    $data = array(
      'modules' => $modules,
      'cities' => $cities,
      'roles' => $roles,
      'user' => $user
    );
    return view('users.registro')->with($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function update(Request $request, $id)
  { }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function inactive($id)
  {
    $user = User::find($id);
    $user->status = "inactive";
    $user->save();
    return redirect('user');
  }

  public function active($id)
  {
    $user = User::find($id);
    $user->status = "active";
    $user->save();
    return redirect('user');
  }
  public function destroy($id)
  {
    //
  }

  public function changerol()
  {
    $roles = Auth::user()->roles->sortBy('module_id');
    $new_roles = array();
    foreach ($roles as $role) {
      # code...
      if ($role->id != 3) {
        array_push($new_roles, $role);
      }
    }
    //return $new_roles;
    return view('auth.change')->with('roles', $new_roles);
  }
  public function postchangerol(Request $request)
  {
    $sw = false;


    $roles = Auth::user()->roles;
    foreach ($roles as $rol) {
      # code...
      if ($request->rol_id == $rol->id) {
        $sw = true;
      }
    }


    if ($sw) {
      Session::put('rol_id', $request->rol_id);
      $rol = Role::find($request->rol_id);
      Session::put('rol_name', $rol->name);
    }

    return redirect('/');
  }
}
