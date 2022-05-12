<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesAndPermissions\StoreRoleRequest;
use App\Http\Resources\RolesResource;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\RolesAndPermissions\RolePermission;
use App\Services\RolesAndPermissions\RolesAndPermissionsService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;


class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => [
            'roles' =>RolesResource::collection(CustomRole::all()),
            ]
        ],202);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role = CustomRole::create(['name' => $request->name]);

            if($request->has('parent_id') && isset($request->parent_id) ){
                $role->setParent($request->parent_id);
            }

            if($request->has('permissions') && !empty($request->permissions) ){
                $parentPermissions = CustomRole::findOrFail($request->parent_id)->permissions->pluck('id')->toArray();
                $permissions = RolesAndPermissionsService::filterPermissionsAccordingToParentPermissions($parentPermissions,$request->permissions);
                $role->givePermissionTo($permissions);

            }

            DB::commit();

            return response()->json(['data' => [
                'message' => 'The role has been created',
                'role' => new RolesResource($role),
            ]],201);

        }catch (\Exception | QueryException $e){
            DB::rollBack();
            return response('Error While creating the role please try again later, the error message: '.$e ,500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  CustomRole  $role
     * @return \Illuminate\Http\Response
     */
    public function show(CustomRole $role)
    {
        return response()->json(['data' => [
            'role' => new RolesResource($role),
        ]],202);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRoleRequest $request, CustomRole $role)
    {
        DB::beginTransaction();

        try {
            $role->update(['name' => $request->name]);

            if($request->has('parent_id') && isset($request->parent_id) ){
                $role->setParent($request->parent_id);
            }

            if($request->has('permissions') && !empty($request->permissions)){
                $parentPermissions = CustomRole::findOrFail($request->parent_id)->permissions->pluck('id')->toArray();
                $permissions = RolesAndPermissionsService::filterPermissionsAccordingToParentPermissions($parentPermissions,$request->permissions);
                $role->setParent($permissions);
            }

            RolePermission::whereIn('role_id' , $role->allChildren($flatten=true))->whereNotIn('permission_id',$permissions)->delete();

            DB::commit();

            return response()->json(['data' => [
                'role' => new RolesResource($role),
            ]],200);

        }catch (\Exception | QueryException $e){
            DB::rollBack();
            return response('Error While creating the role please try again later, the error message: '.$e ,500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CustomRole  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomRole $role)
    {
        $message = '';
        if(!$role->canDeleteRole($message)){
            return response($message,405);
        }

        if($role->delete()){
            return response('The role was deleted',200);
        }
        return response('Error, the role was not deleted',500);

    }
}