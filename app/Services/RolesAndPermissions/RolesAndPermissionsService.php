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

    public static function filterPermissionsAccordingToParentPermissions(Array $parentPermissions,Array $permissions): Array {
        $notAllowedPermissions = array_diff($permissions, $parentPermissions);
        return collect($permissions)->diff($notAllowedPermissions)->all();
    }

    //gets the children role and set each parent and under it its children in a non nested way example:
    // [2] => 1,2
    // [2] => 5,6
    // [2] => 3,4

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


    private static function createSinglePermssion(String $name,Int $parentId=null){

        return CustomPermission::create([
            'name' => $name,
            'parent_id' => $parentId
        ]);

    }
    private static function createSingalRole(String $name,Int $parentId=null){

        return CustomRole::create([
            'name' => $name,
            'parent_id' => $parentId
        ]);

    }

    public static function createRoles(){
        CustomRole::query()->truncate();

        self::createSingalRole('admin');

        $chefEmployee = self::createSingalRole('chef_employee');
        self::createSingalRole('chef_employee1',$chefEmployee->id);

    }

    public static function createPermissions(){
        CustomPermission::query()->truncate();

         //Currency Permission
        $parentCountry= self::createSinglePermssion('CountryController');
       self::createSinglePermssion('CountryController@index',$parentCountry->id );
       self::createSinglePermssion('CountryController@store',$parentCountry->id );
       self::createSinglePermssion('CountryController@show',$parentCountry->id );
       self::createSinglePermssion('CountryController@update',$parentCountry->id );
       self::createSinglePermssion('CountryController@destroy',$parentCountry->id );
       //End of Currency Permission

       //Currency History Permission
       $parentCountry= self::createSinglePermssion('currency_history_permissions');
           self::createSinglePermssion('currency_history_read',$parentCountry->id );
       //End of Currency History Permission

       //Country Permission
        $parentCurrency= self::createSinglePermssion('CurrencyController');
           self::createSinglePermssion('CurrencyController@index',$parentCurrency->id );
           self::createSinglePermssion('CurrencyController@store',$parentCurrency->id );
           self::createSinglePermssion('CurrencyController@show',$parentCurrency->id );
           self::createSinglePermssion('CurrencyController@update',$parentCurrency->id );
           self::createSinglePermssion('CurrencyController@destroy',$parentCurrency->id );
      //End of Country Permission

      //Tag Permission
      $parentTag= self::createSinglePermssion('TagController');
           self::createSinglePermssion('TagController@index',$parentTag->id );
           self::createSinglePermssion('TagController@store',$parentTag->id );
           self::createSinglePermssion('TagController@show',$parentTag->id );
           self::createSinglePermssion('TagController@update',$parentTag->id );
           self::createSinglePermssion('TagController@destroy',$parentTag->id );
      //End of Tag Permission

      //Attribute Permission
      $parentAttribute= self::createSinglePermssion('AttributeController');
           self::createSinglePermssion('AttributeController@index',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@store',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@show',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@update',$parentAttribute->id );
           self::createSinglePermssion('AttributeController@destroy',$parentAttribute->id );
      //End of Attribute Permission

      //Field Permission
      $parentField= self::createSinglePermssion('FieldsController');
           self::createSinglePermssion('FieldsController@index',$parentField->id );
           self::createSinglePermssion('FieldsController@store',$parentField->id );
           self::createSinglePermssion('FieldsController@show',$parentField->id );
           self::createSinglePermssion('FieldsController@update',$parentField->id );
           self::createSinglePermssion('FieldsController@destroy',$parentField->id );
      //End of Field Permission

      //Language Permission
      $parentLanguage= self::createSinglePermssion('LanguageController');
           self::createSinglePermssion('LanguageController@index',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@store',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@show',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@update',$parentLanguage->id );
           self::createSinglePermssion('LanguageController@destroy',$parentLanguage->id );
      //End of Language Permission

      //Label Permission
      $parentLabel= self::createSinglePermssion('LabelController');
           self::createSinglePermssion('LabelController@index',$parentLabel->id );
           self::createSinglePermssion('LabelController@store',$parentLabel->id );
           self::createSinglePermssion('LabelController@show',$parentLabel->id );
           self::createSinglePermssion('LabelController@update',$parentLabel->id );
           self::createSinglePermssion('LabelController@destroy',$parentLabel->id );
      //End of Label Permission

      //Permission Permission
        $parentPermissions= self::createSinglePermssion('PermissionsController');
           self::createSinglePermssion('PermissionsController@index',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@store',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@show',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@update',$parentPermissions->id );
           self::createSinglePermssion('PermissionsController@destroy',$parentPermissions->id );
      //End of Permission Permission

      //Setting Permission
      $parentSetting= self::createSinglePermssion('SettingsController');
           self::createSinglePermssion('SettingsController@index',$parentSetting->id );
           self::createSinglePermssion('SettingsController@store',$parentSetting->id );
           self::createSinglePermssion('SettingsController@show',$parentSetting->id );
           self::createSinglePermssion('SettingsController@update',$parentSetting->id );
           self::createSinglePermssion('SettingsController@destroy',$parentSetting->id );
      //End of Setting Permission




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


}


