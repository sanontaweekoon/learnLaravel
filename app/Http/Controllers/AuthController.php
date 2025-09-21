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
        $token = $user->createToken('my-device')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
