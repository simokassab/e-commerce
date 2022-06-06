<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\MainController;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\StoreAttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Models\Attribute\AttributeValue;
use Attribute;
use Illuminate\Http\Request;

class AttributeValueController extends MainController
{
    const OBJECT_NAME = 'objects.attributeValue';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            return $this->getSearchPaginated(AttributeValueResource::class,AttributeValue::class,$request->data,$request->limit);
        }
        return $this->successResponsePaginated(AttributeValueResource::class,AttributeValue::class);

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
        $attributeValue=new AttributeValue();
        $attributeValue->attribute_id = $request->attribute_id;
        $attributeValue->value = json_encode($request->value);


        if(!$attributeValue->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                'attribute_value' => new AttributeValueResource($attributeValue)
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttributeValue $attributeValue)
    {
        return $this->successResponse(['attribute_value' => new AttributeValueResource($attributeValue)]);

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
    public function update(StoreAttributeValueRequest $request, AttributeValue $attributeValue)
    {
        $attributeValue->attribute_id = $request->attribute_id;
        $attributeValue->value = json_encode($request->value);


        if(!$attributeValue->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
                'attribute_value' => new AttributeValueResource($attributeValue)
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeValue $attributeValue)
    {
        if(!$attributeValue->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                'attribute_value' => new AttributeValueResource($attributeValue)
            ]);
    }
}
