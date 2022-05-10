<?php

namespace App\Services\Currency;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use PhpParser\Node\Expr\Cast\Double;

class CurrencyService {

    public static function updateCurrencyHistory(Currency $currency,Double $newrate){

        if($currency->rate!=$newrate){
            $currency->rate=$newrate;


        }
        if(!$currency->save()){

            return response()->json([
                  'data' => [
                      'message' => 'The rate doesnot updated ! please try again',
                  ]
              ],512);
            }
        return response()->json([
            'data' => [
                'message' => 'rate updated successfully',
                'currency' => new CurrencyResource($currency)
            ]

        ],201);
        $currencyHistory= CurrencyHistory::create([
            'currency_id' => $currency->id,
            'rate' => $newrate

           ]);
    }
    }




