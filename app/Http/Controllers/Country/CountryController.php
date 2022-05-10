<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountry;
use App\Models\Country\Country;
use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use PHPUnit\Framework\Constraint\Count;

class Countries extends Controller
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
                'countries' =>  CountryResource::collection( Country::all()),
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
    public function store(StoreCountry $request)
    {
    //    return  $country = Country::create($request->all());

        $country = new Country();
        $country->name = json_encode($request->name);
        $country->iso_code_1 = ($request->iso_code_1);
        $country->iso_code_2 = ($request->iso_code_2);
        $country->phone_code = ($request->phone_code);
        $country->flag = ($request->flag);

        if(!$country->save()){
            return response()->json([
                'data' => [
                    'message' => 'The country was not created ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'country created successfully',
                'country' => new CountryResource($country)
            ]

        ],201);




    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {

        return response()->json([
            'data' => [
                'country' =>  new CountryResource( $country),
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


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $country->name = json_encode($request->name);
        $country->iso_code_1 = ($request->iso_code_1);
        $country->iso_code_2 = ($request->iso_code_2);
        $country->phone_code = ($request->phone_code);
        $country->flag = ($request->flag);

        if(!$country->save()){
            return response()->json([
                'data' => [
                    'message' => 'The country was not updated ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'country updated successfully',
                'country' => new CountryResource($country)
            ]

        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if(!$country->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The country was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'country deleted successfully',
                'country' => new CountryResource($country)
            ]

        ],201);

    }
}
