<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolesPermissionsController extends Controller
{
    public function index(Request $request){
        info($request->user());
    }

    public function update(Request $request){
        $permissions = $request->all();
        $data['status'] = false;
        try {

            foreach ($permissions['permissions'] as $key => $value) {
                $exists = Permission::where('name', $value)->exists();

                if (!$exists) {
                     info($value);
                    Permission::create(['name' => $value,'guard_name' => 'web']);
                }
            }
             $data['status'] = true;
        } catch (\Throwable $th) {
           info($th->getMessage());
        }

        return response()->json($data);

    }
}
