<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\MainController;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Resources\SettingsResource;
use App\Models\Settings\Setting;
use Illuminate\Http\Request;

class SettingsController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => [
                'settings' => SettingsResource::collection( cache()->remember( 'settings',config('cache.default_cache_time'),fn() => Setting::all() ) ),
            ]
        ],200);

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
    public function store(StoreSettingRequest $request)
    {

        $settings=new Setting();
        $settings->key = json_encode($request->key);
        $settings->value = ($request->value);
        $settings->is_developer = ($request->is_developer);


        if(!$settings->save()){
            return response()->json([
                'data' => [
                    'message' => 'The settings was not created! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'setting created successfully',
                'settings' => new SettingsResource($settings)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return response()->json([
            'data' => [
                'setting' =>  new SettingsResource($setting),
            ]
        ],200);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $setting->title = ($request->title);
        $setting->type = ($request->type);
        $setting->entity = ($request->entity);
        $setting->is_required = ($request->is_required);


        if(!$setting->save()){
            return response()->json([
                'data' => [
                    'message' => 'The settings was not updated ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'settings updated successfully',
                'settings' => new SettingsResource($setting)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        if(!$setting->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The field was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field deleted successfully',
                'setting' => new SettingsResource($setting),
            ]
        ],201);
    }


}
