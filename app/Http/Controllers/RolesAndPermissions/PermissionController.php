<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\Controller;
use App\Models\RolesAndPermissions\CustomPermission;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    function createPermssionParent(String $name,Int $id=null){

        return CustomPermission::create([
            'name' => $name,
            'parent_id' => $id
        ]);

    }
    public function test(){

        //Currency Permission
       $parentCurrency= $this->createPermssionParent('currency_permissions');
            $this->createPermssionParent('currency_create',$parentCurrency->id );
            $this->createPermssionParent('currency_show',$parentCurrency->id );
            $this->createPermssionParent('currency_read',$parentCurrency->id );
            $this->createPermssionParent('currency_update',$parentCurrency->id );
            $this->createPermssionParent('currency_delete',$parentCurrency->id );
        //End of Currency Permission

        //Currency History Permission
        $parentCountry= $this->createPermssionParent('currency_history_permissions');
            $this->createPermssionParent('currency_history_read',$parentCountry->id );
        //End of Currency History Permission

        //Country Permission
       $parentCountry= $this->createPermssionParent('country_permissions');
            $this->createPermssionParent('country_create',$parentCountry->id );
            $this->createPermssionParent('country_show',$parentCountry->id );
            $this->createPermssionParent('country_read',$parentCountry->id );
            $this->createPermssionParent('country_update',$parentCountry->id );
            $this->createPermssionParent('country_delete',$parentCountry->id );
       //End of Country Permission

       //Tag Permission
       $parentTag= $this->createPermssionParent('tag_permissions');
            $this->createPermssionParent('tag_create',$parentTag->id );
            $this->createPermssionParent('tag_show',$parentTag->id );
            $this->createPermssionParent('tag_read',$parentTag->id );
            $this->createPermssionParent('tag_update',$parentTag->id );
            $this->createPermssionParent('tag_delete',$parentTag->id );
       //End of Tag Permission

       //Attribute Permission
       $parentAttribute= $this->createPermssionParent('attribute_permissions');
            $this->createPermssionParent('attribute_create',$parentAttribute->id );
            $this->createPermssionParent('attribute_show',$parentAttribute->id );
            $this->createPermssionParent('attribute_read',$parentAttribute->id );
            $this->createPermssionParent('attribute_update',$parentAttribute->id );
            $this->createPermssionParent('attribute_delete',$parentAttribute->id );
       //End of Attribute Permission

       //Field Permission
       $parentField= $this->createPermssionParent('field_permissions');
            $this->createPermssionParent('field_create',$parentField->id );
            $this->createPermssionParent('field_show',$parentField->id );
            $this->createPermssionParent('field_read',$parentField->id );
            $this->createPermssionParent('field_update',$parentField->id );
            $this->createPermssionParent('field_delete',$parentField->id );
       //End of Field Permission

       //Language Permission
       $parentLanguage= $this->createPermssionParent('language_permissions');
            $this->createPermssionParent('language_create',$parentLanguage->id );
            $this->createPermssionParent('language_show',$parentLanguage->id );
            $this->createPermssionParent('language_read',$parentLanguage->id );
            $this->createPermssionParent('language_update',$parentLanguage->id );
            $this->createPermssionParent('language_delete',$parentLanguage->id );
       //End of Language Permission

       //Label Permission
       $parentLabel= $this->createPermssionParent('label_permissions');
            $this->createPermssionParent('label_create',$parentLabel->id );
            $this->createPermssionParent('label_show',$parentLabel->id );
            $this->createPermssionParent('label_read',$parentLabel->id );
            $this->createPermssionParent('label_update',$parentLabel->id );
            $this->createPermssionParent('label_delete',$parentLabel->id );
       //End of Label Permission

       //Permission Permission
       $parentPermission= $this->createPermssionParent('permission_permissions');
            $this->createPermssionParent('permission_create',$parentLabel->id );
            $this->createPermssionParent('permission_show',$parentLabel->id );
            $this->createPermssionParent('permission_read',$parentLabel->id );
            $this->createPermssionParent('permission_update',$parentLabel->id );
            $this->createPermssionParent('permission_delete',$parentLabel->id );
       //End of Permission Permission

       //Setting Permission
       $parentSetting= $this->createPermssionParent('setting_permissions');
            $this->createPermssionParent('setting_create',$parentSetting->id );
            $this->createPermssionParent('setting_show',$parentSetting->id );
            $this->createPermssionParent('setting_read',$parentSetting->id );
            $this->createPermssionParent('setting_update',$parentSetting->id );
            $this->createPermssionParent('setting_delete',$parentSetting->id );
       //End of Setting Permission



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
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
