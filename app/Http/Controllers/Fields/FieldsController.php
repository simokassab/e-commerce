<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\FieldsStorRequest;
use App\Http\Resources\FieldsResource;
use App\Models\Field\Field;
use Illuminate\Support\Facades\Route;


class FieldsController extends MainController
{

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
        return response()->json([
            'data' => [
                'fields' => FieldsResource::collection(Field::all())
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
    public function store(FieldsStorRequest $request)
    {

        $field=new Field();
        $field->title = json_encode($request->title);
        $field->type = ($request->type);
        $field->entity = ($request->entity);
        $field->is_required = ($request->is_required);


        if(!$field->save()){
            return response()->json([
                'data' => [
                    'message' => 'The field was not created ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field created successfully',
                'field' => new FieldsResource($field)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return response()->json([
            'data' => [
                'field' =>  new FieldsResource($field),
            ]
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(FieldsStorRequest $request, Field $field)
    {
        $field->title = json_encode($request->title);
        $field->type = ($request->type);
        $field->entity = ($request->entity);
        $field->is_required = ($request->is_required);


        if(!$field->save()){
            return response()->json([
                'data' => [
                    'message' => 'The field was not updated ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field updated successfully',
                'field' => new FieldsResource($field)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        if(!$field->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The field was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'field deleted successfully',
                'field' => new FieldsResource($field)
            ]

        ],201);
    }
}
