<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $admin = Admin::where('email', $credentials['email'])->first();

            $item = Str::random(32);
            $token = $admin->createToken($item)->plainTextToken;

            return $token;
        } else {
            return response()->json([
                "message" => "Erro ao realizar login"
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $admin = $request->user();
        $admin->tokens()->delete();

        return response()->json(["logout" => "success"], 200);
    }
}
