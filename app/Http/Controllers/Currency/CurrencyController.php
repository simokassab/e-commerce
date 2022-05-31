<?php

namespace App\Http\Controllers\Currency;

use App\Exceptions\FileErrorException;
use App\Http\Controllers\MainController;
use App\Http\Requests\Currency\StoreCurrency;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Resources\CurrencyHistoryResource;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CurrencyController extends MainController
{
    const OBJECT_NAME = 'objects.currency';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['currencies' => CurrencyResource::collection(Currency::with('currencyHistory')->paginate(config('defaults.default_pagination')))]);

    }

    public function getCurrencyHistories(){

        return $this->successResponse(['currncies_histories' => CurrencyHistoryResource::collection(CurrencyHistory::paginate(config('defaults.default_pagination')))]);

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
    public function store(StoreCurrencyRequest $request)
    {
        $currency = new Currency();
        $currency->name=json_encode($request->name);
        $currency->code=$request->code;
        $currency->symbol=$request->symbol;
        $currency->rate=$request->rate;
        $currency->is_default=$request->is_default;
        if($request->image){
            $currency->image= $this->imageUpload($request->file('image'),config('ImagesPaths.currency.images'));
        }        $currency->sort=$request->sort;

        if(!$currency->save())
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            'currency' => new CurrencyResource($currency)
            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return $this->successResponse(['currency' => new CurrencyResource($currency)]);

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
    public function update(StoreCurrencyRequest $request, Currency $currency)
    {
        DB::beginTransaction();

        try {
            CurrencyService::updateCurrencyHistory($currency,$request->rate);
            $currency->name=json_encode($request->name);
            $currency->code=$request->code;
            $currency->symbol=$request->symbol;
            $currency->rate=$request->rate;
            $currency->is_default=$request->is_default;

            if($request->image){
                if( !$this->removeImage($currency->image) ){
                     throw new FileErrorException();
                 }
                $currency->image= $this->imageUpload($request->file('image'),config('ImagesPaths.currency.images'));

             }
            $currency->sort=$request->sort;
            $currency->save();
            DB::commit();

            return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'currency' => new CurrencyResource($currency)
        ]);

        }catch(\Exception $exception){
            DB::rollBack();
            return response()->json([
                'data' => [
                    'message' => 'currency was not updated the error message: '.$exception->getMessage(),
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
//        if(!$currency->delete()){
//            return response()->json([
//                'data' => [
//                    'message' => 'The currency was not deleted ! please try again later',
//                ]
//            ],512);
//        }
//
//        return response()->json([
//            'data' => [
//                'message' => 'currency deleted successfully',
//                'currency' => new CurrencyResource($currency)
//            ]
//
//        ],201);
    }
}
