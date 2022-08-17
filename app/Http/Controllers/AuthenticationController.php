<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthenticationController extends MainController
{
    public function login(LoginRequest $request){

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(! auth()->attempt($validated)){
            return $this->errorResponse('Sorry, but you entered the wrong credentials!',[],-1,401);
        }

        $permissions = [];

        if(!is_null(\auth()->user()->roles) && count(\auth()->user()->roles) > 0){
            $permissions = \auth()->user()->roles[0]->permissions;
        }

        return $this->successResponse(
            'Authenticated Successfully! ',
            [
                'user' => \auth()->user(),
                'permissions' => $permissions
            ]
            ,1,202);

    }


    public function logout(){
        return Auth::logout();
    }
}
