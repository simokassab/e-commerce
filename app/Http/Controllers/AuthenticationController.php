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
                'permissions' => $permissions,
                'token' => \auth()->user()->createToken('app-token', ['service'])->plainTextToken

            ]
            ,1,202);

    }

    public function thirdPartyLogin(LoginRequest $request){

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(! auth()->attempt($validated)){
            return $this->errorResponse('Sorry, but you entered the wrong credentials!',[],-1,401);
        }


        if(is_null(\auth()->user()->roles) || \auth()->user()->roles[0]->name != 'hxa'){
            Auth::logout();
            return $this->errorResponse(message:'Error! the user you logged in is not for general APIs', statusCode:403);
        }


        return $this->successResponse(
            'Authenticated Successfully! ',
            [
                'token' => \auth()->user()->createToken('app-token', ['service'])->plainTextToken
            ]
            ,1,202);

    }


    public function logout(){
        Auth::logout();
        return $this->successResponse('Logout Successfully!');
    }

    public static function thirdPartyLogout(){
        if(\auth()->user()){
        \auth()->user()->tokens()->delete();
        }
        Auth::logout();

    }

}
