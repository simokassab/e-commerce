<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreLanguage;
use App\Http\Resources\LanguageResource;
use App\Models\Language\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class LanguageController extends Controller
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
                'languages' => LanguageResource::collection(  Language::all())
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
    public function store(StoreLanguage $request)
    {
        $language=new Language();
        $language->name=$request->name;
        $language->code=$request->code;
        $language->is_default=$request->is_default;
        $language->is_disabled=$request->is_disabled;
        $language->image=$request->image;
        $language->sort=$request->sort;

        if(!$language->save()){
            return response()->json([
                'data' => [
                    'message' => 'The Language was not created ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'language created successfully',
                'language' => new LanguageResource($language)
            ]

        ],201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        return response()->json([
            'data' => [
                'language' =>  new LanguageResource($language),
            ]
        ],200);
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
        $language->is_default=$request->is_default;
        $language->is_disabled=$request->is_disabled;
        $language->image=$request->image;
        $language->sort=$request->sort;

        if(!$language->save()){
            return response()->json([
                'data' => [
                    'message' => 'The Language was not updated ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'language updated successfully',
                'language' => new LanguageResource($language)
            ]

        ],201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        if(!$language->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The language was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'language deleted successfully',
                'language' => new LanguageController($language)
            ]

        ],201);
    }
    }

