<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        $pageNo = $request->pageNo ?? 1;
        $limit = $request->limit ?? 10;

        $data = [
            'data' => [],
            'count' => 0,
            'status' => false,
        ];


        try {
            $data['totalCount'] = User::count();
            $paginatedData = User::latest()->paginate($limit, ['*'], 'page', $pageNo);
            $users = $paginatedData->getCollection()->map(function ($user) {
                $dataToReturn = $user->userData();
                return $dataToReturn;
            });
            $paginatedData->setCollection($users);
            $data['data'] = $paginatedData;
            $data['count'] = $data['count'];
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data['status'] = false;

        try {

            $phone = Helper::validPhone($request->phone);
            if (!$phone['status']) {
                throw new \Exception($phone['message']);
            }


            if (!User::where('email', $request->email)->first()) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $phone['phoneNumber'],
                    'password' => Hash::make($request->password),
                ]);
                $role = Role::where('id', $request->role_id)->first();
                // info($role->guard_name);
                $user->assignRole($role);

                $data['status'] = true;
            } else {
                throw new \Exception("Please use another email", 1);
            }
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }

    public function show($userId)
    {
        $data['status'] = false;
        try {
            $data['user'] = User::where('id', $userId)->first()->userData();
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }
    public function update(Request $request)
    {
        $data['status'] = false;



        try {

            $phone = Helper::validPhone($request->phone);
            if (!$phone['status']) {
                throw new \Exception($phone['message']);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                throw new Exception('user does not exist', 1);
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'phone' => $phone['phoneNumber'],
            ]);
            $role = $user->getRoleById($request->role_id);
            if (!$role) {
                throw new \Exception('Role does not exist', 1);
            }
            // Remove existing roles and assign the new role
            $user->syncRoles([$role]);
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }
    public function delete(Request $request)
    {
        $userId =  $request->userId;

        $data['status'] = false;
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception("user not found");
            }
            $user->delete();
            $data['status'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }
        return response()->json($data);
    }
}
