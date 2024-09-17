<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public static function store(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:users,name|between:3,30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'passwordc' => 'required|same:password'
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'The payload is not formatted correctly',
                'errors' => $validation->errors()
            ], 422);
        }

        $data = $validation->validated();

        User::create($data);

        return response()->json([
            'status' => 201,
            'message' => 'User successfully created.'
        ], 201);
    }

    public static function login(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'The payload is not formatted correctly',
                'errors' => $validation->errors()
            ], 422);
        }

        $credentials = $request->only('name', 'password');

        $user = User::where('name', $credentials['name'] ?? '')->first();

        if (!$user) {
            return response()->json([
                'error' => 'Username not found',
                'status' => 401,
            ], 401);
        }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();

            $expirationTimeInMinutes = 60;
            $token = $user->createToken('authToken', ['*'], null, 3600)->accessToken;
            $token->expires_at = now()->addMinutes($expirationTimeInMinutes);
            $token->save();

            return response()->json([
                'token' => $token->token,
                'expire' => $token->expires_at,
                'id' => $user->id,
                'status' => 200
            ]);
        }

        return response()->json([
            'password' => 'Password incorrect',
            'status' => 401
        ], 401);
    }
}
