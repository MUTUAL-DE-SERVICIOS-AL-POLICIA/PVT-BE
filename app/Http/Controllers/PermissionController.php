<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Permission;
use Muserpol\RolePermission;
use Illuminate\Http\Request;
use Muserpol\Models\Module;
use Muserpol\Models\Role;
use Muserpol\Action;
use Muserpol\Operation;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $modules = Module::all();
        $roles = Role::all();
        $actions = Action::all();
        $modulesL=array('' =>'');
        $operations = Operation::all();
        $permissions = Permission::join('operations','permissions.operation_id','=','operations.id')
                                  ->select('permissions.id','permissions.operation_id','permissions.action_id','operations.name')
                                  ->get();
        $role_permissions = RolePermission::all();
        foreach ($modules as $module){
            $modulesL[$module->id]=$module->name;
        }
        $data = array(
            'modules'=> $modules,
            'roles' => $roles,
            'modulesL' => $modulesL,
            'actions' => $actions,
            'permissions' => $permissions,
            'operations' => $operations,
            'role_permissions' => $role_permissions
        );
        return view('permissions.registro')->with($data);
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
        $permission = new Permission;   
        $permission->name = $request->name;
      
        $permission->roles()->attach($request->rol);
              
        $permission->save();        
        return redirect('user');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
