<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Module;
use Muserpol\Models\City;
use Muserpol\Models\Role;
use Muserpol\Models\RoleUser;
use Muserpol\RolePermission;
use Muserpol\Permission;
use Muserpol\Action;
use Muserpol\User;

class InternSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::whereUsername('asistente')->first();
    if (!$user) {
      $user = User::create([
        'username' => 'asistente',
        'first_name' => 'Asistente',
        'last_name' => 'Asistente',
        'password' => Hash::make('asistente'),
        'position' => 'Asistente',
        'status' => 'active',
        'city_id' => City::first()->id
      ]);
    }

    $modules = Module::where('name', 'Fondo de Retiro')->orWhere('name', 'Cuota y Auxilio Mortuorio')->get();
    $roles = [];
    foreach ($modules as $module) {
      $role = Role::whereName('Asistente')->where('module_id', $module->id)->first();
      if (!$role) {
        $role = Role::create([
          'module_id' => $module->id,
          'name' => 'Asistente',
          'action' => 'Observador',
        ]);
      }
      $roles[] = $role;
    }

    $action = Action::where('name', 'read')->first();
    $permissions = Permission::where('action_id', $action->id)->get();
    foreach ($permissions as $permission) {
      foreach ($roles as $role) {
        $role_permission = RolePermission::where('role_id', $role->id)->where('permission_id', $permission->id)->first();
        if (!$role_permission) {
          $role_permission = new RolePermission();
          $role_permission->role()->associate($role);
          $role_permission->permission()->associate($permission);
          $role_permission->save();
        }
      }
    }

    foreach ($roles as $role) {
      $role_user = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();
      if (!$role_user) {
        $user->roles()->attach($role);
      }
    }
  }
}
