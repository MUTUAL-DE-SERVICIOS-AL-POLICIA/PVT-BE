<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use User;
use Auth;
use Session;
use Muserpol\Models\Role;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
   

    public function index()
    {
          return view('home');
    }

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
