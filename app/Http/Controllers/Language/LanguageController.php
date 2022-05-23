<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\MainController;
use App\Http\Requests\Language\StoreLanguage;
use App\Http\Resources\LanguageResource;
use App\Models\Language\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;


class LanguageController extends MainController
{
    const OBJECT_NAME = 'objects.language';

    public function __construct($defaultPermissionsFromChild = null)
    {
        parent::__construct($defaultPermissionsFromChild);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['languages' => LanguageResource::collection(Language::all())]);
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
    public function store(StoreLanguage $request)
    {
        $language=new Language();
        $language->name=$request->name;
        $language->code=$request->code;
       if($request->is_default){
        $language->setIsDefault();
       }
        $language->is_disabled=$request->is_disabled;
        $language->image=$request->image;
        $language->sort=$request->sort;

        if(!$language->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'language' => new LanguageResource($language)
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        return $this->successResponse(['language' => new LanguageResource($language)]);
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
    public function update(Request $request, Language $language)
    {
        $language->name=$request->name;
        $language->code=$request->code;
        if($request->is_default){
            $language->setIsDefault();
           }
        $language->is_disabled=$request->is_disabled;
        $language->image=$request->image;
        $language->sort=$request->sort;

        if(!$language->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'language' => new LanguageResource($language)
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        if(!$language->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'language' => new LanguageResource($language)
        ]);

    }
    public function setLang($locale){

        session(['locale' => $locale]);
        App::setLocale($locale);

     return $this->successResponse([__('objects')]);

    }
    }

