<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\Tax\StoreTaxRequest;
use App\Http\Resources\TagResource;
use App\Http\Resources\TaxResource;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxController extends MainController
{
    const OBJECT_NAME = 'objects.tax';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $relations=['taxComponent'];
        if ($request->method()=='POST') {
            $searchKeys=['name','percentage','is_complex','complex_behavior'];
            return $this->getSearchPaginated(TaxResource::class, Tax::class,$request, $searchKeys,$relations);

        }
        return $this->successResponsePaginated(TaxResource::class,Tax::class,$relations);

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
    public function store(StoreTaxRequest $request)
    {

    $tax=new Tax();
    $tax->name = json_encode($request->name);
    $tax->is_complex = $request->is_complex;
    $tax->percentage = $request->percentage;
    $tax->complex_behavior = $request->complex_behavior;

    if($request->is_complex){

        $columns = [
            'tax_id',
            'component_tax_id',
            'sort'
       ];
       if($request->components)
            batch()->insert(new TaxComponent(),$columns,$request->components,500);
        // return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        }

    if(!$tax->save())
        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        'Taxes' => new TaxResource($tax)
]);


    return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        return $this->successResponse(['tax' => new TagResource($tax)]);
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
    public function update(StoreTaxRequest $request, Tax $tax)
    {
        $tax->name = json_encode($request->name);
        $tax->is_complex = $request->is_complex;
        $tax->percentage = $request->percentage;
        $tax->complex_behavior = $request->complex_behavior;

        if($request->is_complex){

            $columns = [
                'tax_id',
                'component_tax_id',
                'sort'
           ];
           if($request->components)
                batch()->insert(new TaxComponent(),$columns,$request->components,500);
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

            }
        if(!$tax->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'Taxes' => new TaxResource($tax)
    ]);


        return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax,TaxComponent $taxComponent)
    {
        if(!$tax->delete())
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
        'taxes' => new TaxResource($tax)
    ]);
    }
}
