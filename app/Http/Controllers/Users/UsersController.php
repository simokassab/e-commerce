<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends MainController
{
    const OBJECT_NAME = 'objects.user';

    public function index(Request $request){

        if ($request->method()=='POST') {
            $data=$request->data;
            $roleName = $request->data['role_name'] ?? '';
            $keys = array_keys($data ?? []);
            $searchKey = ['first_name','last_name','email','username'];
            $user=User::with(['roles' => fn($query) => $query->select('name')])
                ->whereHas('roles',fn ($query) => $query->whereRaw('lower(name) like (?)',["%$roleName%"]))
                ->where(function($query) use($data,$keys,$searchKey){
                        foreach($keys as $key) if(in_array($key,$searchKey))
                            $query->where($key,'LIKE', '%'.$data[$key].'%');
                })
                ->paginate($request->limit ?? config('defaults.default_pagination'));

            return UserResource::collection($user);

        }

        return $this->successResponse(['user',UserResource::collection(User::with('roles')->paginate(config('defaults.default_pagination')))],200);
    }

    public function store(StoreUserRequest $request){

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'salt' => $request->salt ?? '',
                'password' => Hash::make($request->password),
            ]);
            $user->AssignRole($request->role_id);
            DB::commit();

                 return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                 'user' => new UserResource($user)
             ]);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->errorResponse(['message' => 'user was not created successfully the error message: '.$exception]);
        }
    }

    public function show(User $user)
    {
        return $this->successResponse(['user' => new UserResource($user)]);

    }

    public function update(StoreUserRequest $request, User $user)
    {
        $user->username =  $request->username;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name =$request->last_name;
        $user->salt = $request->salt;
        $user->is_disabled=$request->is_disabled ?? $user->is_disabled;

        if(!($user->save()))
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'user' => new UserResource($user)
        ]);
    }

    public function destroy(User $user)
    {
        if(!$user->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'user' => new UserResource($user)
        ]);

    }

    public function toggleStatus(Request $request ,$id){

        $request->validate([
            'is_disabled' => 'boolean|required'
        ]);

        $user = User::findOrFail($id);
        $user->is_disabled=$request->is_disabled;
        if(!$user->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'user' =>  new UserResource($user)
        ]);

    }
}
