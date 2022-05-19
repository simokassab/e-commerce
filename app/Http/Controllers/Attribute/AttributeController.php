<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\MainController;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AttributeController extends MainController
{
    public function __construct($defaultPermissionsFromChild = null)
    {
    dd(basename(Route::currentRouteAction()));
    parent::__construct($defaultPermissionsFromChild);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => [
                'attributes' => AttributeResource::collection(  Attribute::with('attributeValues')->get())
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
    public function store(StoreAttributeRequest $request)
    {
        $attribute=new Attribute();
        $attribute->title=json_encode($request->title);


        if(!$attribute->save()){
            $data = [
                'message' => 'The attribute was not created ! please try again later',
            ];
            return $this->errorResponse($data);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute created successfully',
                'attribute' => new AttributeResource($attribute)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return response()->json([
            'data' => [
                'attribute' =>  new AttributeResource($attribute),
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
    public function update(Request $request, Attribute $attribute)
    {
        $attribute->title=json_encode($request->title);


        if(!$attribute->save()){
            return response()->json([
                'data' => [
                    'message' => 'The attribute was not updated ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute updated successfully',
                'attribute' => new AttributeResource($attribute)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        if(!$attribute->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The attribute was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute deleted successfully',
                'attribute' => new AttributeResource($attribute)
            ]

        ],201);
    }
}
