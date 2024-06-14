<?php

namespace App\Http\Controllers;

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

            if(!User::where('email',$request->email )->first()){
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                $token = $user->createToken('AuthToken')->plainTextToken;
                $data['status'] = true;
                $data['user'] = $user;
                $data['token'] = $token;
            }else{
                $data['message'] = "user already exists";
            }

        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);

    }
    public function loginUser(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'status'=> true,
                'token' => $token,
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function logoutUser(Request $request){

        $data['status'] = false;

        try {
             $request->user()->tokens()->delete();
             $data['status'] = true;
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        return response()->json($data);

    }

    // public function verifyEmail(Request $request)
    // {

    //     $data = false;
    //     $email = $request->email;

    //     if ($email) {
    //         if (User::where("email", $email)->first()) {
    //             $data  = true;
    //         }
    //     }

    //     return $data;
    // }
    // public function resetPassword(Request $request)
    // {

    //     $verify = $this->verifyEmail($request);
    //     if ($verify) {
    //         if ($user = User::where('email', $request->email)->first()) {
    //             $user->update([
    //                 'password' => bcrypt($request->password)
    //             ]);

    //             return $this->login($request);
    //         }
    //     }

    //     return $verify;
    // }

}
