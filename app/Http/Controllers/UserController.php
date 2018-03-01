<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\User;
use Auth;
use Session;
use Muserpol\Models\Role;
use Muserpol\Helpers\Util;
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
return view('users.index');
}
/**
* Process datatables ajax request.
*
* @return \Illuminate\Http\JsonResponse
*/
public function anyData()
{
$users = User::all();
//DB::statement(DB::raw('set @rownum=0'));
return Datatables::of($users)
        ->addColumn('action', function ($u) {
return '<a href="#edit-'.$u->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            })
        ->editColumn('city_id',function ($u)
        {
return $u->city->name ?? ""; 
        })
        ->editColumn('first_name', function($u)
        {
return $u->first_name.' '.$u->last_name ?? "";
        })
        // ->setRowId('phone')
        //     ->setRowClass(function ($u) {
        //         return $u->id % 2 == 0 ? 'alert-success' : 'alert-warning';
        //     })
        //     ->setRowData([
        //         'phone' => 'test',
        //     ])
        // ->setRowAttr([
        //     'color' => 'red',
        //     ])
        ->make(true);
        
} 

    public function Data()
    {
        $users = User::select(['id','username', 'first_name', 'last_name','position', 'phone','status','city_id'])->where('id', '>', 1);
        return Datatables::of($users)
            ->addColumn('name', function ($user) { return Util::ucw($user->first_name) . ' ' . Util::ucw($user->last_name); })
            ->addColumn('module', function ($user) { return $user->roles()->first()->module()->first()->name; })
            ->addColumn('city', function ($user) { return $user->city()->first()->name; })
            ->addColumn('role', function ($user): string { 
                 $roles_list=[];
                 foreach ($user->roles as $role) {
                     $roles_list[]=$role->name;
                 }
                return implode(",",$roles_list);
            })
            ->addColumn('status', function ($user) { return $user->status == 'active' ? 'Activo' : 'Inactivo'; })
            ->addColumn('action', function ($user) { return  $user->status == "active" ?
                '<div class="btn-group" style="margin:-3px 0;">
                    <a href="user/'.$user->id.'/edit "class="btn btn-primary btn-raised btn-sm">&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;</a>
                    <a href="" class="btn btn-primary btn-raised btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span>&nbsp;</a>
                    <ul class="dropdown-menu">
                        <li><a href="user/block/'.$user->id.' " style="padding:3px 5px;"><i class="glyphicon glyphicon-ban-circle"></i> Bloquear</a></li>
                    </ul>
                </div>' :
                '<div class="btn-group" style="margin:-3px 0;">
                    <a href="user/'.$user->id.'/edit " class="btn btn-primary btn-raised btn-sm">&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i>&nbsp;&nbsp;</a>
                    <a href="" class="btn btn-primary btn-raised btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span>&nbsp;</a>
                    <ul class="dropdown-menu">
                        <li><a href="user/unblock/'.$user->id.' " style="padding:3px 5px;"><i class="glyphicon glyphicon-ok-circle"></i> Activar</a></li>
                    </ul>
                </div>';})->make(true);
    }
    //hasta aca---------

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changerol()
    {
        $roles =  Auth::user()->roles;
        // return $roles;
        // dd($roles);
        $new_roles = array();
        foreach ($roles as $role) {
            # code...
            if($role->id!=3)
            {
                array_push($new_roles, $role);
            }
        }
        //return $new_roles;

        return view('auth.change')->with('roles',$new_roles);
    }
    public function postchangerol(Request $request)
    {
       $sw = false;
       
        
        $roles = Auth::user()->roles;

        foreach ($roles as $rol) {
            # code...
            if($request->rol_id==$rol->id)
            {
                $sw = true;
            }
        }
       

       if($sw)
       {
         Session::put('rol_id',$request->rol_id);
         $rol = Role::find($request->rol_id);
         Session::put('rol_name',$rol->name);
       }
       
       return redirect('/');

    }

}
