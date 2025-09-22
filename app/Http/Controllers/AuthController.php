<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register
    public function register(Request $request){
        // Validate field
        $fields = $request->validate([
            "fullname" => "required|string",
            "username" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string|confirmed",
            "tel" => "required",
            "role" => "required|integer"
        ]);

        // Create
        $user = User::create([
            "fullname" => $fields['fullname'],
            "username" => $fields["username"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),
            "tel" => $fields["tel"],
            "role" => $fields["role"]
        ]);

        // Create token
        $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);

    }

    // Login
    public function login(Request $request){
        // Validate field
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
        ]);

        // Create
        $user = User::where("email", $fields["email"])->first();
        
        //Check password
        if(!$user || Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Invalid Login'
            ]);
        }else{
            // ลบ Token เก่าออกก่อนค่อยสร้างใหม่
            $user -> tokens()->delete();

            // Create token
             $token = $user->createToken($request->userAgent(), ["$user->role"])->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token
            ];
    
            return response($response, 201);
        }
    }

    // Logout
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message' => "Logouted"
        ];
    }
}
