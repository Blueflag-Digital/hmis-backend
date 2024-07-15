<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        $data['status'] = false;
        try {
            $data['roles'] = Role::get();
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }
    public function store(Request $request)
    {
        $data['status'] = false;
        try {
            Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data['status'] = false;
        $role_id = $request->role_id;
        try {
            if(!$role = Role::find($role_id)){
                throw new \Exception("role Not found", 1);

            }
            $role->update([
                'name' => $request->name,
            ]);
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data['status'] = false;
        try {
            if ($role = Role::find($request->role_id)) {
                $role->delete();
            }
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }
}
