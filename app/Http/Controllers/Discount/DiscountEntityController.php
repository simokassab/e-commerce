<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\MainController;
use App\Http\Requests\Discount\StoreDiscountEntityRequest;
use App\Http\Resources\DiscountEntityResource;
use App\Models\Discount\DiscountEntity;
use Exception;
use Illuminate\Http\Request;

class DiscountEntityController extends MainController
{
    const OBJECT_NAME = 'objects.discountEntity';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {

                $arrayKeys=['discount_id','category_id','brand_id','tag_id'];
                $data=$request->data;
                $keys = array_keys($data);
                $rows = DiscountEntity::where(function($query) use($keys,$data,$arrayKeys){
                    foreach($keys as $key){
                        if(in_array($key,$arrayKeys)){
                            $value=strtolower($data[$key]);
                            $query->whereRaw('lower('.$key.') like (?)',["%$value%"]);
                                }
                        else{
                            throw new Exception();
                            }
                            }
                    })->paginate($pagination ?? config('defaults.default_pagination'));

                return  DiscountEntityResource::collection($rows);

        }
        return $this->successResponsePaginated(DiscountEntityResource::class,DiscountEntity::class);

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
    public function store(StoreDiscountEntityRequest $request)
    {
        $discountEntity = new DiscountEntity();
        $discountEntity->discount_id = $request->discount_id;
        $discountEntity->category_id = $request->category_id;
        $discountEntity->brand_id = $request->brand_id;
        $discountEntity->tag_id = $request->tag_id;

        if(!($discountEntity->save()))
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'discount_entity' => new DiscountEntityResource($discountEntity)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DiscountEntity $discountEntity)
    {
        return $this->successResponse(['discount_entity' => new DiscountEntityResource($discountEntity)]);

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
    public function update(StoreDiscountEntityRequest $request, DiscountEntity $discountEntity)
    {
        $discountEntity->discount_id = $request->discount_id;
        $discountEntity->category_id = $request->category_id;
        $discountEntity->brand_id = $request->brand_id;
        $discountEntity->tag_id = $request->tag_id;

        if(!($discountEntity->save()))
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'discount_entity' => new DiscountEntityResource($discountEntity)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscountEntity $discountEntity)
    {
        if(!$discountEntity->delete())
        return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])]);

     return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
         'discount_entity' => new DiscountEntityResource($discountEntity)
     ]);
    }
}
