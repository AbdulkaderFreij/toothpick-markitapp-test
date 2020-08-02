<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $user = User::create([
            'name'=> $request -> name,
            'email' => $request -> email,
            'password' => $request -> password=\bcrypt(request('password')),
        ]);
        $token = auth()->login($user);
        return $this->respondWithToken($token);

    }

    public function login()
    {
        $credentials = request([
            'email','password'
            ]);

        if (!$token = auth()->attempt($credentials)) {
            return response() -> json(['error' => 'unauthorized!'], 404);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message', 'Succesfully logged out!']);
    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();
        return response()-> json([
            'access_token' => $token,
            'user'=> $user,
            'token_type'=> 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}