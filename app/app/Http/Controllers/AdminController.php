<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends Controller
{
    const REGISTER_VALIDATE_RULES     = [
        'username' => 'required|string',
        'email'    => 'required|email|unique:users',
        'password' => 'required|string|min:6|max:50'
    ];

    const AUTHENTICATE_VALIDATE_RULES = [
        'email'    => 'required|email',
        'password' => 'required|string|min:6|max:50'
    ];

    public function register(Request $request)
    {
        $data      = $request->only('username', 'email', 'password');
        $validator = Validator::make($data, self::REGISTER_VALIDATE_RULES);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
        }

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator   = Validator::make($credentials, self::AUTHENTICATE_VALIDATE_RULES);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
        ], Response::HTTP_OK);
    }
}
