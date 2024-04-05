<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'User or password is incorrect'], 401);
        }

        $token = $user->createToken($user->name . '-Masagena@@')->plainTextToken;

        return (new UserResource($user))->additional([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = request()->user();
        $user->tokens()->delete();
        $user->currentAccessToken()->delete();

        return response()->json(["message" => "Success Logout"]);
    }
}
