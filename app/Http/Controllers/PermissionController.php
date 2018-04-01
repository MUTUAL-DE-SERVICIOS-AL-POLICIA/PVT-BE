<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Permission;
use Muserpol\RolePermission;
use Illuminate\Http\Request;
use Muserpol\Models\Module;
use Muserpol\Models\Role;
use Muserpol\Action;
use Muserpol\Operation;
use Log;

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
                                  ->select('permissions.id','permissions.operation_id','operations.module_id','permissions.action_id','operations.name')
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
        // return $request->all();
        $module_id = $request->module_id;
        $role_id = $request->role_id;
        //verifica la existencia de permisos caso contrario se crea uno cechus y anita XD a perdon era karen
   
        foreach ($request->permissions_list as $p) {
            # code...
            $permission_request = (Object)$p;

            $permission_create = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',1)->first();
            if(!$permission_create)
            {
                $permission_create = new Permission();
                $permission_create->operation_id = $permission_request->operation_id;
                $permission_create->action_id = 1;
                $permission_create->save();
                Log::info($permission_create);
            }

            $permission_read = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',2)->first();
            if(!$permission_read)
            {
                $permission_read = new Permission();
                $permission_read->operation_id = $permission_request->operation_id;
                $permission_read->action_id = 2;
                $permission_read->save();
                Log::info($permission_read);
            }

            $permission_update = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',3)->first();
            if(!$permission_update)
            {
                $permission_update = new Permission();
                $permission_update->operation_id = $permission_request->operation_id;
                $permission_update->action_id = 3;
                $permission_update->save();
                Log::info($permission_update);
            }

            $permission_delete = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',4)->first();
            if(!$permission_delete)
            {
                $permission_delete = new Permission();
                $permission_delete->operation_id = $permission_request->operation_id;
                $permission_delete->action_id = 4;
                $permission_delete->save();
                Log::info($permission_delete);
            }

            $permission_print = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',5)->first();
            if(!$permission_print)
            {
                $permission_print = new Permission();
                $permission_print->operation_id = $permission_request->operation_id;
                $permission_print->action_id = 5;
                $permission_print->save();
                Log::info($permission_print);
            }

            // Log::info(json_encode($permission_request));
            // $observer = $permission;
        }

        

        //limpiando permisos del rol hdp
            
        $role_permissions = RolePermission::where('role_id',$role_id)->get();
        foreach ($role_permissions as $cyk) {
            # code...
            $cyk->delete();
        }

        //asisgando permisos al rol hdp 

        foreach ($request->permissions_list as $p) {

            $permission_request = (Object)$p;

            Log::info('role'.$permission_request->name);
            Log::info('operation_id'.$permission_request->operation_id);
            
            if($permission_request->create=='true')
            {
                Log::info('can create');
                $permission_create = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',1)->first();
                $role_permission_create = new RolePermission();
                $role_permission_create->role_id = $role_id;
                $role_permission_create->permission_id = $permission_create->id;
                $role_permission_create->save();
                Log::info($role_permission_create);

            }
            
            if($permission_request->read=='true')
            {
                Log::info('can read');
                $permission_read = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',2)->first();
                $role_permission_read = new RolePermission();
                $role_permission_read->role_id = $role_id;
                $role_permission_read->permission_id = $permission_read->id;
                $role_permission_read->save();
                Log::info($role_permission_read);
            }

            if($permission_request->update=='true')
            {
                Log::info('can update');
                $permission_update = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',3)->first();
                $role_permission_update = new RolePermission();
                $role_permission_update->role_id = $role_id;
                $role_permission_update->permission_id = $permission_update->id;
                $role_permission_update->save();
                Log::info($role_permission_update);
            }
            
            if($permission_request->delete=='true')
            {
                Log::info('can delete');
                $permission_delete = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',4)->first();
                $role_permission_delete = new RolePermission();
                $role_permission_delete->role_id = $role_id;
                $role_permission_delete->permission_id = $permission_delete->id;
                $role_permission_delete->save();
                Log::info($role_permission_delete);

            }
            
            if($permission_request->print=='true')
            {
                Log::info('can print');
                $permission_print = Permission::where('operation_id',$permission_request->operation_id)->where('action_id',5)->first();
                Log::info(json_encode($permission_print));
                $role_permission_print = new RolePermission();
                $role_permission_print->role_id = $role_id;
                $role_permission_print->permission_id = $permission_print->id;
                $role_permission_print->save();
                Log::info($role_permission_print);
            }

        }

        // $obj = (Object) array('nom' => 1,'XD2'=>'asd' );
        return 'cechuz ==>> Karen XD';  
        // $permission = new Permission;   
        // $permission->name = $request->name;
      
        // $permission->roles()->attach($request->rol);
              
        // $permission->save();        
        // return redirect('user');
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
