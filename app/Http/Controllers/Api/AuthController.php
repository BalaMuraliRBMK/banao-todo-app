<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use Auth;

class AuthController extends Controller
{


    //Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:8',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => "Request Success but error occured",
                "errors" => $validator->messages(),
                "code" => 422,
            ], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('Token of '.$user->name)->plainTextToken;
        $user_id = $user->id;
        $username = $user->name;
        return response()->json([
            "status" => "User Created Successfully",
            "token" => $token,
            "user_id" => $user_id,
            "username" => $username,
            "code" => 200
        ], 200);
    }


    //Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8|max:8',
        ]);
        if($validator->fails()){
            return response()->json([
                "status" => "Request Success but error occured",
                "errors" => $validator->messages(),
                "code" => 422,
            ], 200);
        }
        $user = User::where('email', $request->email)->first();
        if($user){
            $passwordInDB = $user->password;
            if(Hash::check($request->password, $passwordInDB)){
                $token = $user->createToken('Token of '.$user->name)->plainTextToken;
                $user_id = $user->id;
                $username = $user->name;
                return response()->json([
                    "status" => "User Logged In Successfully",
                    "token" => $token,
                    "user_id" => $user_id,
                    "username" => $username,
                    "code" => 200
                ],200);
            }
            else{
                return response()->json([
                    'status' => 'Request Success but Password Mismatch',
                    "code" => 401
                ],200);
            }
        }
        else{
            return response()->json([
                'status' => 'Request Success but No User Found',
                "code" => 404
            ],200);
        }
    }

    //Logout
    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => 'User Logged Out',
            "code" => 200
        ],200);
    }
}
