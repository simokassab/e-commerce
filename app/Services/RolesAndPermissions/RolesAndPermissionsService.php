<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsService {

    public static function givePermissionToParentRoleAndChildren(array|Permission $permissions , CustomRole $roles) {
        return $roles->allChildren();

    }

    public static function getRoleChildren(int | Role $role, $flatten= false) : Array
    {
        // got all the roles
        $allRoles = CustomRole::all(); //get all roles info

        // we passed the main role that we want to get its children along with the roles and there children
        $roleChildren = self::generateChildrenForAllRoles($allRoles);

        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $roleId = (is_numeric($role) ? $role : $role->id);


        return self::drawRoleChildren($roleId, $roleChildren,!$flatten, $allRoles);
    }

    private static function generateChildrenForAllRoles($allRoles):Array {
        $roleChildren = [];
        foreach($allRoles as $currentRole){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentRole->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($roleChildren[$parentId])){
                $roleChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $roleChildren[$parentId][] = $currentRole->id;
        }

        return $roleChildren;

    }

    private static function drawRoleChildren(Int $parentRoleId, Array $allRolesID ,$isMultiLevel = false, $allRoles): Array{ //with levels
        if(empty($allRolesID[$parentRoleId])){
            return [];
        }

        foreach($allRolesID[$parentRoleId] as $childRoleId){
            if($isMultiLevel){
                $childRoles[$childRoleId] = ['data' => $allRoles->find($childRoleId), 'children' => []];
                $childRoles[$childRoleId]['children'] = self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles);
            }
            else{
                $childRoles[] = $childRoleId;
                $childRoles = array_merge($childRoles, self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles));
            }
        }
        return $childRoles;
    }

    public static function filterPermissionsAccordingToParentPermissions(Array $parentPermissions,Array $permissions): Array {
        $notAllowedPermissions = array_diff($permissions, $parentPermissions);
        return collect($permissions)->diff($notAllowedPermissions)->all();
    }

    public function storeRole(){

    }


    // public static function generateRelationStringForRoleChildren(int | Role $role): String
    // {
    //     $old_count = 0;
    //     $relations = "children";
    //     $query = CustomRole::find($role);
    //     $current_count = collect($query->load($relations))->flatten()->count();


    //     while($current_count > $old_count){
    //         $old_count = collect($query->load($relations))->flatten()->count();
    //         $relations .= ".children";
    //         $current_count = collect($query->load($relations))->flatten()->count();
    //     }

    //     return $relations;
    // }

    private static function createSinglePermssion(String $name,Int $id=null){

        return CustomPermission::create([
            'name' => $name,
            'parent_id' => $id
        ]);

    }
    public static function createPermissions(){
        CustomPermission::query()->truncate();
         //Currency Permission
       $parentCurrency= self::createSinglePermssion('currency_permissions');
       self::createSinglePermssion('currency_create',$parentCurrency->id );
       self::createSinglePermssion('currency_show',$parentCurrency->id );
       self::createSinglePermssion('currency_read',$parentCurrency->id );
       self::createSinglePermssion('currency_update',$parentCurrency->id );
       self::createSinglePermssion('currency_delete',$parentCurrency->id );
   //End of Currency Permission

   //Currency History Permission
   $parentCountry= self::createSinglePermssion('currency_history_permissions');
       self::createSinglePermssion('currency_history_read',$parentCountry->id );
   //End of Currency History Permission

   //Country Permission
  $parentCountry= self::createSinglePermssion('country_permissions');
       self::createSinglePermssion('country_create',$parentCountry->id );
       self::createSinglePermssion('country_show',$parentCountry->id );
       self::createSinglePermssion('country_read',$parentCountry->id );
       self::createSinglePermssion('country_update',$parentCountry->id );
       self::createSinglePermssion('country_delete',$parentCountry->id );
  //End of Country Permission

  //Tag Permission
  $parentTag= self::createSinglePermssion('tag_permissions');
       self::createSinglePermssion('tag_create',$parentTag->id );
       self::createSinglePermssion('tag_show',$parentTag->id );
       self::createSinglePermssion('tag_read',$parentTag->id );
       self::createSinglePermssion('tag_update',$parentTag->id );
       self::createSinglePermssion('tag_delete',$parentTag->id );
  //End of Tag Permission

  //Attribute Permission
  $parentAttribute= self::createSinglePermssion('attribute_permissions');
       self::createSinglePermssion('attribute_create',$parentAttribute->id );
       self::createSinglePermssion('attribute_show',$parentAttribute->id );
       self::createSinglePermssion('attribute_read',$parentAttribute->id );
       self::createSinglePermssion('attribute_update',$parentAttribute->id );
       self::createSinglePermssion('attribute_delete',$parentAttribute->id );
  //End of Attribute Permission

  //Field Permission
  $parentField= self::createSinglePermssion('field_permissions');
       self::createSinglePermssion('field_create',$parentField->id );
       self::createSinglePermssion('field_show',$parentField->id );
       self::createSinglePermssion('field_read',$parentField->id );
       self::createSinglePermssion('field_update',$parentField->id );
       self::createSinglePermssion('field_delete',$parentField->id );
  //End of Field Permission

  //Language Permission
  $parentLanguage= self::createSinglePermssion('language_permissions');
       self::createSinglePermssion('language_create',$parentLanguage->id );
       self::createSinglePermssion('language_show',$parentLanguage->id );
       self::createSinglePermssion('language_read',$parentLanguage->id );
       self::createSinglePermssion('language_update',$parentLanguage->id );
       self::createSinglePermssion('language_delete',$parentLanguage->id );
  //End of Language Permission

  //Label Permission
  $parentLabel= self::createSinglePermssion('label_permissions');
       self::createSinglePermssion('label_create',$parentLabel->id );
       self::createSinglePermssion('label_show',$parentLabel->id );
       self::createSinglePermssion('label_read',$parentLabel->id );
       self::createSinglePermssion('label_update',$parentLabel->id );
       self::createSinglePermssion('label_delete',$parentLabel->id );
  //End of Label Permission

  //Permission Permission
  $parentPermission= self::createSinglePermssion('permission_permissions');
       self::createSinglePermssion('permission_create',$parentLabel->id );
       self::createSinglePermssion('permission_show',$parentLabel->id );
       self::createSinglePermssion('permission_read',$parentLabel->id );
       self::createSinglePermssion('permission_update',$parentLabel->id );
       self::createSinglePermssion('permission_delete',$parentLabel->id );
  //End of Permission Permission

  //Setting Permission
  $parentSetting= self::createSinglePermssion('setting_permissions');
       self::createSinglePermssion('setting_create',$parentSetting->id );
       self::createSinglePermssion('setting_show',$parentSetting->id );
       self::createSinglePermssion('setting_read',$parentSetting->id );
       self::createSinglePermssion('setting_update',$parentSetting->id );
       self::createSinglePermssion('setting_delete',$parentSetting->id );
  //End of Setting Permission


  

    }

}


