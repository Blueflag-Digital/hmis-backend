<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsController extends Controller
{
    public function getRolePermissions($roleId){
         $data['status'] = false;
        try {
            $role = Role::find($roleId);
            $data['permissions'] = $role ? $role->permissions->pluck('name') : collect();
            $data['role'] = $role;

           $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }
    public function getUserPermissions(Request $request)
    {
        $data['status'] = false;
        try {

            $user = $request->user();
            $role = $user->roles->first();
            $permissions = $role ? $role->permissions->pluck('name') : collect();

            $data['role'] = $role;
            $data['permissions'] = $permissions;
           $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    public function updatePermissions(Request $request)
    {
        $permissions = $request->all();
        $data['status'] = false;
        try {

            foreach ($permissions['permissions'] as $key => $value) {
                $exists = Permission::where('name', $value)->exists();

                if (!$exists) {
                    Permission::create(['name' => $value, 'guard_name' => 'web']);
                }
            }
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json($data);
    }
    public function updateRoles(Request $request){
        $roles = $request->all();
        $data['status'] = false;
        try {

            foreach ($roles['roles'] as $key => $value) {
                $exists = Role::where('name', $value)->exists();
                if (!$exists) {
                    Role::create(['name' => $value, 'guard_name' => 'web']);
                }
            }
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        return response()->json($data);
    }

    public function updateRolePermissions(Request $request)
    {
        $data['status'] = false;
        try {
            $roleId = $request->roleId;
            $selectedPermissions = $request->selectedPermissions;

            if (!$role = Role::find($roleId)) {
                throw new \Exception("Role not found", 1);
            }
            $validPermissions = Permission::whereIn('name', $selectedPermissions)->where('guard_name', 'web')->pluck('name')->toArray();

            if (empty($validPermissions)) {
                throw new \Exception('No valid permissions found');
            }


            $role->syncPermissions($selectedPermissions);
            $data['status'] = true;
            $data['message'] = 'Permissions updated successfully.';
        } catch (\Throwable $th) {
            $data['message'] =  $th->getMessage();
        }

        return response()->json($data);
    }
}
