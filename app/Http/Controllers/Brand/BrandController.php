<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand\Brand;
use Illuminate\Http\Request;

class BrandController extends MainController
{
    const OBJECT_NAME = 'objects.brand';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return $this->successResponse(['brands' => BrandResource::collection(Brand::all())]);
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
    public function store(StoreBrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = json_encode($request->name);
        $brand->code = $request->code;
        $brand->title = json_encode($request->title);
        $brand->description = json_encode($request->description);
        $brand->keyword = json_encode($request->keyword);
        $brand->sort = $request->sort;
        $brand->is_disabled = $request->is_disabled;

        if(!($brand->save()))
            return $this->errorResponse([__('messages.failed.create',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse([__('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse(['brand' => new BrandResource($brand)]);

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
    public function update(Request $request, Brand $brand)
    {
        $brand->name = json_encode($request->name);
        $brand->code = $request->code;
        $brand->title = json_encode($request->title);
        $brand->description = json_encode($request->description);
        $brand->keyword = json_encode($request->keyword);
        $brand->sort = $request->sort;
        $brand->is_disabled = $request->is_disabled;

        if(!($brand->save()))
            return $this->errorResponse([__('messages.failed.update',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse([__('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if(!$brand->delete())
           return $this->errorResponse([__('messages.failed.delete',['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse([__('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            'brand' => new BrandResource($brand)
        ]);

}}
