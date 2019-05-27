<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Role;
use Muserpol\RolePermission;
use Muserpol\Action;
use Muserpol\Permission;
use Muserpol\Operation;

class EcoComPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        foreach (Operation::where('module_id', 2) as $o) {
            foreach (Action::whereIn('id', [1, 2, 3, 4]) as $a) {
                $permission = Permission::where('operation_id', $o->id)
                    ->where('action_id', $a->id)
                    ->first();
                if (!$permission) {
                    $permission = new Permission();
                    $permission->operation_id = $o->id;
                    $permission->action_id = $a->id;
                    $permission->save();
                }
            }
        }
        $this->createRolePermission(85, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(95, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(97, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(98, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(100, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(103, [2, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);
        $this->createRolePermission(105, [2, 4, 23, 22, 24, 25, 26, 27, 52], [1, 2, 3]);

        $this->createRolePermission(85, [4], [2, 3]);
        $this->createRolePermission(97, [4], [2, 3]);
        $this->createRolePermission(98, [4], [2, 3]);
        $this->createRolePermission(103, [4], [2, 3]);
        $this->createRolePermission(95, [4], [2, 3]);
        $this->createRolePermission(96, [4], [2]);
        $this->createRolePermission(99, [4], [2]);
        $this->createRolePermission(100, [4], [2]);
        $this->createRolePermission(104, [4], [2]);

        $this->createRolePermission(85, [5], [2, 3]);
        $this->createRolePermission(95, [5], [2, 3, 4]);
        $this->createRolePermission(96, [5], [1, 2, 3, 4]);
        $this->createRolePermission(97, [5], [2, 3, 4]);
        $this->createRolePermission(98, [5], [2, 3, 4]);
        $this->createRolePermission(99, [5], [1, 2, 3, 4]);
        $this->createRolePermission(100, [5], [2, 3, 4]);
        $this->createRolePermission(103, [5], [2, 3, 4]);
        $this->createRolePermission(104, [5], [1, 2, 3, 4]);
        $this->createRolePermission(105, [5], [1, 2, 3, 4]);
    }
    public function createRolePermission($operation_id, $roles, $actions)
    {
        foreach ($actions as $action_id) {
            foreach ($roles as $role_id) {
                $permission = Permission::where('operation_id', $operation_id)
                    ->where('action_id', $action_id)
                    ->first();
                if ($permission) {
                    $role_permission = RolePermission::where('role_id', $role_id)
                        ->where('permission_id', $permission->id)
                        ->first();
                    if (!$role_permission) {
                        $role_permission = new RolePermission();
                        $role_permission->role()->associate($role_id);
                        $role_permission->permission()->associate($permission);
                        $role_permission->save();
                    }
                }
            }
        }
    }
}
