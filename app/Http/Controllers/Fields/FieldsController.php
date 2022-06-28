<?php

namespace App\Http\Controllers\Fields;

use App\Http\Controllers\MainController;
use App\Http\Requests\Field\StoreFieldRequest;
use App\Http\Resources\FieldsResource;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Services\Field\FieldService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $relations=['fieldValue'];
        if ($request->method()=='POST') {
            $searchKeys=['title','type','entity','is_required'];
            return $this->getSearchPaginated(FieldsResource::class, Field::class,$request, $searchKeys,$relations);

        }
        return $this->successResponsePaginated(FieldsResource::class,Field::class,$relations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $field->type = $request->type;
        $field->entity = $request->entity;
        $field->is_required = $request->is_required;

        $check=true;

        if(!$field->save())
          return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

          if($request->type=='select' && $request->field_value){
           $fieldValueArray=$request->field_value;
           foreach ($request->field_value as $fieldValue => $value){
              $fieldValueArray[$fieldValue]["field_id"] = $field->id;
              $fieldValueArray[$fieldValue]["value"] = json_encode($request->field_value[$fieldValue]['value']);
            }
            $check = FieldValue::insert($fieldValueArray);
            }

            if(!$check)
                return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);


        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'field' => new FieldsResource($field)
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
        DB::beginTransaction();
        try {
        FieldService::deleteRelatedfieldValues($field);

        $field->title = json_encode($request->title);
        $field->type = $request->type;
        $field->entity = $request->entity;
        $field->is_required = $request->is_required;
        $field->save();

        if($request->type=='select' && $request->field_value){
            $fieldValueArray=$request->field_value;
            foreach ($request->field_value as $fieldValue => $value){
               $fieldValueArray[$fieldValue]["field_id"] = $field->id;
               $fieldValueArray[$fieldValue]["value"] = json_encode($request->field_value[$fieldValue]['value']);
            }
            FieldValue::insert($fieldValueArray);
             }

        DB::commit();
        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'field' => new FieldsResource($field)
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        DB::beginTransaction();
        try {
            FieldService::deleteRelatedfieldValues($field);
            $field->delete();

            DB::commit();
            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                'field' => new FieldsResource($field)
            ]);

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        }
    }
}
