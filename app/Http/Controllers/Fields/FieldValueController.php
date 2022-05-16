<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\StoreFieldsValueRequest;
use App\Http\Resources\FieldsValueResource;
use App\Models\Field\FieldValue;
use Illuminate\Http\Request;

class FieldValueController extends MainController
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
                'fields_value' => FieldsValueResource::collection(FieldValue::all())
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
    public function store(StoreFieldsValueRequest $request)
    {
        $field_value=new FieldValue();
        $field_value->fields_id = $request->fields_id;
        $field_value->value = json_encode($request->value);


        if(!$field_value->save()){
            return response()->json([
                'data' => [
                    'message' => 'The field value was not created ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field value created successfully',
                'field_value' => new FieldsValueResource($field_value)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FieldValue $field_value)
    {
        return response()->json([
            'data' => [
                'field_value' =>  new FieldsValueResource($field_value),
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
     * @param  FieldValue  $field_value
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FieldValue $field_value)
    {
        $field_value->fields_id = $request->fields_id;
        $field_value->value =json_encode($request->value);


        if(!$field_value->save()){
            return response()->json([
                'data' => [
                    'message' => 'The field value was not updated ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field value updated successfully',
                'field_value' => new FieldsValueResource($field_value)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FieldValue  $field_value
     * @return \Illuminate\Http\Response
     */
    public function destroy(FieldValue $field_value)
    {
        if(!$field_value->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The field value was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field value deleted successfully',
                'field_value' => new FieldsValueResource($field_value)
            ]

        ],201);
    }
}
