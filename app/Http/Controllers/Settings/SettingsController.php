<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\MainController;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Resources\Setting\SettingsResource;
use App\Http\Resources\Setting\SingleSettingResource;
use App\Models\Settings\Setting;
use Exception;
use Illuminate\Http\Request;


class SettingsController extends MainController
{

    const OBJECT_NAME = 'objects.setting';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['title', 'value'];
            return $this->getSearchPaginated(SettingsResource::class, Setting::class, $request, $searchKeys);
        }

        return $this->successResponsePaginated(SettingsResource::class, Setting::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     **/
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSettingRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        // // // // // // // //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreSettingRequest $request, Setting $setting)
    {
        $settingValue="";
        $findedSetting=Setting::find($request->key);

        if($findedSetting){
            $setting->value=$settingValue;
            $setting->save();
        }

        else{
           return $this->errorResponse('The value type must be the same as the setting type');
        }



    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Setting $setting)
    {
    }


    public function getTableHeaders()
    {
        return $this->successResponse('Success!', [
            'headers' => __('headers.settings'),
            'column_data' => [
                    'key',
                    'title',
                    'name',
                    'value',
            ]
        ]);
    }
}
