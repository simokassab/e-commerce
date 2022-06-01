<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\StoreFieldsValueRequest;
use App\Http\Resources\FieldsValueResource;
use App\Models\Field\FieldValue;
use Illuminate\Http\Request;

class FieldValueController extends MainController
{
    const OBJECT_NAME = 'objects.fieldValue';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['fields_values' => FieldsValueResource::collection(FieldValue::paginate(config('defaults.default_pagination')))]);
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
        $fieldValue=new FieldValue();
        $fieldValue->fields_id = $request->fields_id;
        $fieldValue->value = json_encode($request->value);


        if(! $fieldValue->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'field_value' => new FieldsValueResource($fieldValue)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FieldValue $fieldValue)
    {

        return $this->successResponse(['field_value' => new FieldsValueResource($fieldValue)]);
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
     * @param  FieldValue  $fieldValue
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFieldsValueRequest $request, FieldValue $fieldValue)
    {
        $fieldValue->fields_id = $request->fields_id;
        $fieldValue->value =json_encode($request->value);


        if(! $fieldValue->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'field_value' => new FieldsValueResource($fieldValue)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FieldValue  $fieldValue
     * @return \Illuminate\Http\Response
     */
    public function destroy(FieldValue $fieldValue)
    {
        if(! $fieldValue->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'field_value' => new FieldsValueResource($fieldValue)
        ]);
    }
}
