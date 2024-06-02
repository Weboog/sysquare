<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Exception as GlobalException;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6', //Password::defaults();
//            'role' => [
//                'required',
//                Rule::in([Role::SAMURAI_KEY, Role::ADMIN_KEY, Role::SELLER_KEY])
//            ]
        ];


        //Validate request
        try {
            $credentials = $request->validate($rules);
        } catch (GlobalException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        //Attempt authenticate
        if (!Auth::attempt($credentials)){
            return response()->json(['error' => 'AUTH_ERROR', 'message' => 'USER_NOT_FOUND'], 404);
        }

        $accessToken  = Auth::user()->createToken(Date::now(), ['*'])->accessToken;
        return response()->json([
            'user' => new UserResource(Auth::user()),
            'accessToken' => $accessToken
        ]);

    }

    public function logout() {
        $user = Auth::user();
        $user->tokens()->update(['revoked' => 1]); //Revoke all access tokens of that user
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
