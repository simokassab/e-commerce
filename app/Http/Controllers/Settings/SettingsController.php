<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\MainController;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Resources\SettingsResource;
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

        if ($request->method()=='POST') {
            $searchKeys=['title','value','is_developer'];
            // return $this->getSearchPaginated(SettingsResource::class, Setting::class,$request, $searchKeys);
            $data=$request->data;
            $keys = array_keys($data);
            $rows = Setting::where(function($query) use($keys,$data,$searchKeys){
                    foreach($keys as $key){
                        if(in_array($key,$searchKeys)){
                            $value=strtolower($data[$key]);

                            $query->whereRaw('lower('.$key.') like (?)',["%$value%"]);
                                }
                        else{
                            throw new Exception();
                            }
                            }
                    })->paginate($request->limit ?? config('defaults.default_pagination'));

            return  SettingsResource::collection($rows);
        }
       return $this->successResponsePaginated(SettingsResource::class,Setting::class);
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettingRequest $request)
    {
        $setting=new Setting();
        $setting->title = json_encode($request->title);
        $setting->value = $request->value;
        $setting->is_developer = $request->is_developer;

        if(!$setting->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'setting' => new SettingsResource($setting)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return $this->successResponse(['setting' => new SettingsResource($setting)]);
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
    public function update(StoreSettingRequest $request, Setting $setting)
    {
        $setting->title = $request->title;
        $setting->value = $request->value;
        $setting->is_developer = $request->is_developer;


        if(!$setting->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'setting' => new SettingsResource($setting)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        if(!$setting->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'setting' => new SettingsResource($setting)
        ]);
    }


}
