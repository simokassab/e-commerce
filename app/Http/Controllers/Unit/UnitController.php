<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => [
                'unit' => UnitResource::collection(  Unit::all())
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
    public function store(StoreUnitRequest $request)
    {
        $unit=new Unit();
        $unit->name=json_encode($request->name);
        $unit->code=$request->code;


        if(!$unit->save()){
            return response()->json([
                'data' => [
                    'message' => 'The unit was not created ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'unit created successfully',
                'unit' => new UnitResource($unit)
            ]

        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        return response()->json([
            'data' => [
                'unit' =>  new UnitResource($unit),
            ]
        ],200);
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
    public function update(Request $request, Unit $unit)
    {
        $unit->name=json_encode($request->name);
        $unit->code=$request->name;


        if(!$unit->save()){
            return response()->json([
                'data' => [
                    'message' => 'The unit was not updated ! please try again later',
                ]
                ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'unit updated successfully',
                'unit' => new UnitResource($unit)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        if(!$unit->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The unit was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'unit deleted successfully',
                'unit' => new UnitResource($unit)
            ]

        ],201);

    }
}
