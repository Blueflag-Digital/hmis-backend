<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function store(Request $request)
    {
        $data['status'] = false;
        $data['user'] = null;
        $data['message']  = "";

        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6',
        // ]);
        //  return $validatedData;

        try {

            if (!User::where('email', $request->email)->first()) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                $token = $user->createToken('AuthToken')->plainTextToken;
                $data['status'] = true;
                $data['user'] = $user;
                $data['token'] = $token;
            } else {
                $data['message'] = "user already exists";
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }
    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            //get the business hospital currecny used
            $currency = Helper::getHospCurrency($user);

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user->user_data,
                'currency'=> $currency
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function logoutUser(Request $request)
    {

        $data['status'] = false;

        try {
            $request->user()->tokens()->delete();
            $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);
    }
    public function updatePassword(Request $request){
        $data = [
            'status'=>false,
            'message' => "Failed to update password",
            'last_changed' => ""
        ];
        $user = $request->user();
        try {
            $user->password = Hash::make($request->password);
            $user->update();
            $data['status'] = true;
            $data['message'] = 'Success.Use the new password upon next login';
            $data['last_changed'] = $user->updated_at->diffForHumans();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);

    }
}
