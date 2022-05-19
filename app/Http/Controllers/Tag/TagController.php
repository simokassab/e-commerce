<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\MainController;
use App\Http\Requests\Tag\StoreTag;
use App\Http\Resources\TagResource;
use App\Models\Tag\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TagController extends MainController
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
                'tag' => TagResource::collection(  Tag::all())
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
    public function store(StoreTag $request)
    {
        $tag=new Tag();
        $tag->name=json_encode($request->name);


        if(!$tag->save()){
            return response()->json([
                'data' => [
                    'message' => 'The tag was not created ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'tag created successfully',
                'tag' => new TagResource($tag)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return response()->json([
            'data' => [
                'tag' =>  new TagResource($tag),
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
    public function update(Request $request, Tag $tag)
    {
        $tag->name=json_encode($request->name);


        if(!$tag->save()){
            return response()->json([
                'data' => [
                    'message' => 'The tag was not updated ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'tag updated successfully',
                'tag' => new TagResource($tag)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if(!$tag->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The tag was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'tag deleted successfully',
                'tag' => new TagResource($tag)
            ]

        ],201);
    }
    }

