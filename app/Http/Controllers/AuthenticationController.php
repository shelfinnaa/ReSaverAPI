<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'The email has not been registered'], 422);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['error' => 'Password is wrong'], 422);
        }

        // return $user->createToken('user login')->plainTextToken;
        return response()->json($user->createToken('user login')->plainTextToken);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function logininfo(Request $request)
    {
        return response()->json(Auth::user());
    }
}
