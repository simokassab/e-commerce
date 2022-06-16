<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $request->session()->regenerate();

        session(['user' => auth()->user()]);


        return $this->successResponse([
            'message' => 'authenticated successfully!',
            'user' => auth()->user(),
        ],201);
    }


    public function logout(){
        return Auth::logout();
    }
}
