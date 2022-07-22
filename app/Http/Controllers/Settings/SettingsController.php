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

        $findedSetting = Setting::find($request->key);
        if($findedSetting){
            if($request->type==$findedSetting->type){
                if(gettype($request->value)=='array'){
                    $finalValues="";
                    foreach ($request->value as $key => $value)
                        $finalValues=implode(',',$request->value);

                    $setting->value=$finalValues;
                }else{

                    $setting->value = $request->value;
                }

                $setting->save();
                return $this->successResponse(
                    __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                    [
                        'setting' => new SettingsResource($setting)
                    ]
                );

            }
            else{
                return $this->errorResponse(
                    __('messages.error.update', ['name' => __(self::OBJECT_NAME)]),
                    [
                        'setting' => new SettingsResource($setting)
                    ]
                );
            }
        }
    //     $finalValues="";
    //     if(gettype($request->value)=="array"){
    //     foreach ($request->value as $key => $value) {
    //         $finalValues.=$value.",";
    //     }
    //     $setting->value = $finalValues;
    // }else{
    //     $setting->value=$request->value;
    // }
        if (!$setting->save())
            return $this->errorResponse(__('messages.failed.update', ['name' => __(self::OBJECT_NAME)]));

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'setting' => new SettingsResource($setting)
            ]
        );
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
