<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\User;
use Auth;
use Session;
use Muserpol\Models\Role;
use Muserpol\Helpers\Util;
use Muserpol\Models\Module;
use Muserpol\Models\City;

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
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDatatable()
    {
        $users = User::with('roles')->get();
        return Datatables::of($users)
            ->addColumn('action', function ($u) {
                return '<a href="/user/' . $u->id . '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>Edit</a>';
            })
            ->editColumn('city_id', function ($u) {
                return $u->city->name ?? "";
            })
            ->editColumn('first_name', function ($u) {
                return $u->first_name . ' ' . $u->last_name ?? "";
            })            
            ->addColumn('button-roles', function($u) {
                return '<a class="btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Ver</a>';
            })
            ->addColumn('state', function($u) {
                return '<a class="btn btn-xs btn-primary"><i class="fa fa-arrow-circle-up"></i> Cambiar</a>';
                })->rawColumns(['state','action','button-roles'])
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
            ->addColumn('role', function ($user) : string {
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
                </div>' :
                    '<div class="btn-group" style="margin:-3px 0;">
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
        $cities = City::all()->pluck('name', 'id');
        $citiesT= City::all();
        $roles = Role::all();
        $citiesL=array('' =>'');
        foreach ($citiesT as $city){
            $citiesL[$city->id]=$city->name;
        }
        $data = array(
            'modules'=> $modules,
            'cities' => $cities,
            'roles' => $roles,
            'citiesL' => $citiesL
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
        // if ($id != null) {
        //     //editar
        //     $user=User::find($id);   
        // }else {
        //     //registrar
        //     $user = new User;   
        // }       
        if($request->has('user_id'))
        {
            $user=User::find($request->user_id);  
            // return $user;
        }else
        {
            $user = new User;
            // dd($user);
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->position = $request->position;
        $user->username = $request->username;
        $user->city_id=$request->city;
        if($request->contra == "false"){       
            if($request->password){
                $user->password = bcrypt(trim($request->password));
                $user->remember_token= bcrypt(trim($request->remember_token));
            }           
        }else{
            if($request->password){
                $user->password = bcrypt(trim($request->password));
                $user->remember_token= bcrypt(trim($request->remember_token));
            }           
        }      
        $user->save();
        if (isset($user)){
            $user->roles()->sync($request->rol, false);
        }else{
            $user->roles()->attach($request->rol);
        }       
        $user->save();        
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modules = Module::all();
        $cities = City::all()->pluck('name', 'id');
        $roles = Role::all();
        $user=User::where('id', $id) ->first();
        $data = array(
            'modules'=> $modules,
            'cities' => $cities,
            'roles' => $roles,
            'user' =>$user
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
    { 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        $user=User::find($id);
        $user->status = "inactive";
        $user->save();
        return redirect('user');
    }

    public function active($id)
    {
        $user=User::find($id);
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
        $roles = Auth::user()->roles;
        // return $roles;
        // dd($roles);
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
