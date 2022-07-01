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
use Exception;
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
    public function index(Request $request)
    {
        $relations=['currencyHistory'];
        if ($request->method()=='POST') {
            $searchKeys=['name','code','symbol','rate'];
            $data= $this->getSearchPaginated(CurrencyResource::class, Currency::class,$request, $searchKeys,$relations);
            if($data->isEmpty()){
                    $data=[
                        'data' => [
                            [
                            'id' => '',
                            'name'=>'',
                            'code'=>'',
                            'symbol'=> '',
                            'rate'=> '',
                            'is_default'=> '',
                            'is_default'=> '',
                        ]
                        ]
                    ];
                    return response()->json($data);
                    return  CurrencyResource::collection($data);
                }
                return $data;
        }
        return $this->successResponsePaginated(CurrencyResource::class,Currency::class,['currencyHistory']);

    }

    public function getCurrencyHistories(){

        return $this->successResponsePaginated(CurrencyHistoryResource::class,CurrencyHistory::class);
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
        if($request->is_default){
            $currency->setIsDefault();
        }
        if($request->image){
            $currency->image= $this->imageUpload($request->file('image'),config('image_paths.currency.images'));
        }

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
            $currency->name=json_encode($request->name);
            $currency->code=$request->code;
            $currency->symbol=$request->symbol;
            $currency->rate=$request->rate;


            if($request->image){
                if( !$this->removeImage($currency->image) ){
                     throw new FileErrorException();
                 }
                $currency->image= $this->imageUpload($request->file('image'),config('image_paths.currency.images'));
             }
             $currency->save();
            DB::commit();

            return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'currency' => new CurrencyResource($currency)
        ]);

        }catch(\Exception $exception){
            DB::rollBack();

            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]),[
                $exception->getMessage()
            ] ]);

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
    public function setCurrencyIsDefault($currency){

        $currencyObject = Currency::findOrFail($currency);
        $currencyObject->setIsDefault();
        $currencyObject->save();

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
        'currency' => new CurrencyResource($currencyObject)
        ]);
    }
}
