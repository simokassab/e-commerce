<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\StoreAttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Models\Attribute\AttributeValue;
use Attribute;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
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
                'attribute_value' => AttributeValueResource::collection(AttributeValue::all())
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
    public function store(StoreAttributeValueRequest $request)
    {
        $attribute_value=new AttributeValue();
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = json_encode($request->value);


        if(!$attribute_value->save()){
            return response()->json([
                'data' => [
                    'message' => 'The attribute value was not created ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute value created successfully',
                'attribute_value' => new AttributeValueResource($attribute_value)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttributeValue $attribute_value)
    {
        return response()->json([
            'data' => [
                'attribute_value' =>  new AttributeValueResource($attribute_value),
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
    public function update(Request $request, AttributeValue $attribute_value)
    {
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = json_encode($request->value);


        if(!$attribute_value->save()){
            return response()->json([
                'data' => [
                    'message' => 'The attribute value was not updated ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute value updated successfully',
                'attribute_value' => new AttributeValueResource($attribute_value)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeValue $attribute_value)
    {
        if(!$attribute_value->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The attribute value was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'attribute value deleted successfully',
                'attribute_value' => new AttributeValueResource($attribute_value)
            ]

        ],201);
    }
}
