<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\FieldsStorRequest;
use App\Http\Requests\Field\StoreFieldRequest;
use App\Http\Resources\FieldsResource;
use App\Models\Field\Field;
use Illuminate\Support\Facades\Route;


class FieldsController extends MainController
{
    const OBJECT_NAME = 'objects.field';

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
        return $this->successResponse(['fields' => FieldsResource::collection(Field::with('fieldValue')->get())]);

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
    public function store(StoreFieldRequest $request)
    {

        $field=new Field();
        $field->title = json_encode($request->title);
        $field->type = ($request->type);
        $field->entity = ($request->entity);
        $field->is_required = ($request->is_required);


        if(!$field->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'fields' => new FieldsResource($field)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return $this->successResponse(['field' => new FieldsResource($field)]);

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
    public function update(StoreFieldRequest $request, Field $field)
    {
        $field->title = json_encode($request->title);
        $field->type = ($request->type);
        $field->entity = ($request->entity);
        $field->is_required = ($request->is_required);


        if(!$field->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'fields' => new FieldsResource($field)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        if(!$field->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'fields' => new FieldsResource($field)
        ]);
    }
}
