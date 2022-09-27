<?php

namespace App\Observers\Currency;

use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use Illuminate\Support\Carbon;

class CurrencyObserver
{
    public function created(Currency $currency)
    {
        $data[] = [
            'id' => $currency->id,
            'rate' => $currency->rate,
            'created_at'  => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ];
        dd($data);
        CurrencyHistory::create($data);
    }

    public function updated(Currency $currency)
    {
        $data[] = [
            'id' => $currency->id,
            'rate' => $currency->rate,
            'created_at'  => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ];
        CurrencyHistory::create($data);
    }
}
