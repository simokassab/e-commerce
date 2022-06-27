<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthenticationController extends MainController
{
    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(! auth()->attempt($validated)){
            return $this->errorResponse([
                'message' => 'wrong credentials',
            ],401);
        }

        return $this->successResponse([
            'message' => 'authenticated successfully!',
            'user' => \auth()->user(),
            'permissions' => \auth()->user()->roles[0]->permissions,
        ],201);
    }


    public function logout(){
        auth()->user()->tokens()->delete();
        return Auth::logout();
    }
}
