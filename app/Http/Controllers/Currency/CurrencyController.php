<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\StoreCurrency;
use App\Http\Resources\CurrencyResource;
use App\Models\Country\Country;
use App\Models\Currency\Currency;
use App\Services\Currency\CurrencyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class CurrencyController extends Controller
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
                'currncies' =>  CurrencyResource::collection(Currency::all()),
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
    public function store(StoreCurrency $request)
    {
        $currency = new Currency();
        $currency->name=json_encode($request->name);
        $currency->code=$request->code;
        $currency->symbol=$request->symbol;
        $currency->rate=$request->rate;
        $currency->is_default=$request->is_default;
        $currency->image=$request->image;
        $currency->sort=$request->sort;

        if(!$currency->save()){
            return response()->json([
                'data' => [
                    'message' => 'The currency was not created ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'currency created successfully',
                'currency' => new CurrencyResource($currency)
            ]

        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return response()->json([
            'data' => [
                'currency' =>  new CurrencyResource( $currency),
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
    public function update(Request $request, Currency $currency)
    {
        // begin transaction put the whole logic inside a try and catch function,
        // the catch will catch any kind of exception and will return response error and rollback the transaction
        DB::beginTransaction();
        try {

            CurrencyService::updateCurrencyHistory($currency,$request->rate);

            $currency->name=json_encode($request->name);
            $currency->code=$request->code;
            $currency->symbol=$request->symbol;
            $currency->rate=$request->rate;
            $currency->is_default=$request->is_default;
            $currency->image=$request->image;
            $currency->sort=$request->sort;

            $currency->save();

            return response()->json([
                'data' => [
                    'message' => 'currency updated successfully',
                    'currency' => new CurrencyResource($currency)
                ]
            ],200);

            DB::commit();

        }catch (Exception $exception){
            DB::rollback();
            return response()->json([
                'data' => [
                    'message' => 'an error occoured the error message: '. $exception->getMessage()
                ]
            ],500);
        }






    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        if(!$currency->delete()){
            return response()->json([
                'data' => [
                    'message' => 'The currency was not deleted ! please try again later',
                ]
            ],512);
        }

        return response()->json([
            'data' => [
                'message' => 'currency deleted successfully',
                'currency' => new CurrencyResource($currency)
            ]

        ],201);
    }
}
