<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\MainController;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use App\Models\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

class AttributeController extends MainController
{
    const OBJECT_NAME = 'objects.attribute';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            return $this->getSearchPaginated(AttributeResource::class,Attribute::class,$request->data,$request->limit);
        }
        return $this->getSearchPaginated(AttributeResource::class,Attribute::class,$request->query,$request->limit);
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

        if(!$attribute->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'attribute' => new AttributeResource($attribute)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return $this->successResponse(['attribute' =>  new AttributeResource($attribute)]);
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
    public function update(StoreAttributeRequest $request, Attribute $attribute)
    {
        $attribute->title=json_encode($request->title);

        if(!$attribute->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'attribute' => new AttributeResource($attribute)
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        if(!$attribute->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                'attribute' => new AttributeResource($attribute)
            ]);

    }
}
