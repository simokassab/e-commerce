<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\User\SingleUserResource;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends MainController
{

    public function login(LoginRequest $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($validated)) {
            return $this->errorResponse('Sorry, but you entered the wrong credentials!', [], -1, 401);
        }

        if(! \auth()->user()->is_active){
            \auth()->logout();
            return $this->errorResponse('Ths user is inactive');
        }

        $permissions = [];

        if (!is_null(\auth()->user()->roles) && count(\auth()->user()->roles) > 0) {
            $permissions = \auth()->user()->roles[0]->permissions;
        }

        return $this->successResponse(
            'Authenticated Successfully! ',
            [
                'token' => auth()->user()->createToken($request->userAgent())->plainTextToken,
                'user' => new SingleUserResource(\auth()->user()),
                'permissions' => $permissions,
            ]
            , 1, 202);

    }

    public function thirdPartyLogin(LoginRequest $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($validated)) {
            return $this->errorResponse('Sorry, but you entered the wrong credentials!', [], -1, 401);
        }


        if (is_null(\auth()->user()->roles) || \auth()->user()->roles[0]->name != 'hxa') {
            auth()->logout();
            return $this->errorResponse(message: 'Error! the user you logged in is not for general APIs', statusCode: 403);
        }


        return $this->successResponse(
            'Authenticated Successfully! ',
            [
                'token' => \auth()->user()->createToken('app-token', ['service'])->plainTextToken
            ]
            , 1, 202);

    }


    public function logout()
    {
        \auth()->logout();
        return $this->successResponse('Logout Successfully!');
    }

    public function thirdPartyLogout()
    {
        if (\auth()->check()) {
            \auth()->user()->tokens()->delete();
        }
        \auth()->logout();
        return $this->successResponse('Logout Successfully!');

    }

}
