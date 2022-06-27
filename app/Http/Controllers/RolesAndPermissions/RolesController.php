<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\MainController;
use App\Http\Requests\RolesAndPermissions\StoreRoleRequest;
use App\Http\Resources\PermissionAllResource;
use App\Http\Resources\RolesResource;
use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\RolesAndPermissions\RolePermission;
use App\Services\RolesAndPermissions\PermissionsServices;
use App\Services\RolesAndPermissions\RolesService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;

class RolesController extends MainController
{
    const OBJECT_NAME = 'objects.role';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['name'];
            //TODO Search also take time more than usual
            return $this->getSearchPaginated(RolesResource::class, CustomRole::class,$request, $searchKeys);
        }
        return $this->successResponsePaginated(RolesResource::class,CustomRole::class);
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
     * @return \Illuminate\Http\JsonResponse
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
                $permissions = RolesService::filterPermissionsAccordingToParentPermissions($parentPermissions,$request->permissions);
                $role->givePermissionTo($permissions);

            }

            DB::commit();
            return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'role' => new RolesResource($role)
        ],201);

        }catch (\Exception | QueryException $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]),
                'error' => $e->getMessage()
            ]);

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
        return $this->successResponse(['role' => new RolesResource($role)],202);
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
     * @return \Illuminate\Http\JsonResponse
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
                $permissions = RolesService::filterPermissionsAccordingToParentPermissions($parentPermissions,$request->permissions);
                $role->givePermissionTo($permissions);
                //TODO  I replaced this line in the if conditon since $permissions give error since undefined
                //TODO when updating the parent id the request take 1min
                //removed the permissions from the children and parent after updating the parent
                RolePermission::whereIn('role_id' , $role->allChildren($flatten=true))->whereNotIn('permission_id',$permissions)->delete();
            }


            DB::commit();

            return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'role' => new RolesResource($role)
        ],200);

        }catch (\Exception | QueryException $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]),
            'error' => $e->getMessage()
        ]);        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CustomRole  $role
     * @return \Illuminate\Http\JsonResponse
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomRole $role)
    {
        //TODO when deleting a role has childern the request take a time more than usual
        $message = '';
        if(!$role->canDeleteRole($message)){
            return $this->errorResponse([$message],405);
        }

        if($role->delete()){
            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                'role' =>  new RolesResource($role)
            ]);
        }
        return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]),
            'role' =>  new RolesResource($role)
        ]);
    }

    public function getNestedPermissionsForRole(CustomRole $role){
        $permissionsOfRole = $role->permissions->toArray();
        $permissions = CustomPermission::with('parent')->get();
        foreach ($permissions as $permission){
            $permissionsWithCheck[] = $permission;
        }
        $nestedPermissions = PermissionsServices::getAllPermissionsNested($permissionsWithCheck,$permissionsOfRole);
        return $this->successResponse($nestedPermissions);

    }
}
