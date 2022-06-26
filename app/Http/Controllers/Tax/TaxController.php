<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\MainController;
use App\Http\Requests\Tax\StoreTaxRequest;
use App\Http\Resources\TaxResource;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use App\Services\Tax\TaxsServices;
use Illuminate\Http\Request;
use PHPUnit\Exception;
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
        $relations=['taxComponents'];
        if ($request->method()=='POST') {
            $searchKeys=['name','percentage','complex_behavior'];
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaxRequest $request)
    {

    $check=true;
    $tax=new Tax();
    $tax->name = json_encode($request->name);
    $tax->is_complex = $request->is_complex;
    $tax->percentage = $request->percentage;
    $tax->complex_behavior = $request->complex_behavior;

    if(!$tax->save())
        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    if($request->is_complex){
        $componentArray = [];
        $columns = [
            'component_tax_id',
            'sort',
            'tax_id',
        ];

       foreach ($request->components as $key => $component) {
            $component['tax_id'] = $tax->id;
            $componentArray[] = $component;
       }

       if($request->components)
            $check = batch()->insert(new TaxComponent(),$columns,$componentArray,500);
        }

        if(!$check)
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
        return $this->successResponse(['tax' => new TaxResource($tax)]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreTaxRequest $request, Tax $tax)
    {
        TaxsServices::deleteRelatedTaxComponents($tax);

        $check=true;
        $tax->name = json_encode($request->name);
        $tax->is_complex = $request->is_complex;
        $tax->percentage = $request->percentage;
        $tax->complex_behavior = $request->complex_behavior;

        if(!$tax->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        if($request->is_complex){
            $componentArray = [];
            $columns = [
                'tax_id',
                'component_tax_id',
                'sort'
           ];
           foreach ($request->components as $key => $component) {
                $component['tax_id'] = $tax->id;
                $componentArray[] = $component;
           }


           if($request->components)
                $check = batch()->insert(new TaxComponents(),$columns,$componentArray);
            }

            if(!$check)
                return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);


        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'Taxes' => new TaxResource($tax)
    ]);


        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tax $tax)
    {
        DB::beginTransaction();
        try {
            TaxsServices::deleteRelatedTaxComponents($tax);
            $tax->delete();

            DB::commit();
            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
                'taxes' => new TaxResource($tax)
            ]);

        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME)]) ]);

        }

    }
}
