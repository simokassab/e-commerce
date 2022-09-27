<?php

namespace App\Observers\Currency;

use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use Illuminate\Support\Carbon;

class CurrencyObserver
{
    public function created(Currency $currency)
    {
        CurrencyHistory::query()->create([
            'currency_id' => $currency->id,
            'rate' => $currency->rate,
            'created_at'  => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ]);
    }

    public function updated(Currency $currency)
    {
        CurrencyHistory::query()->create([
            'rate' => $currency->rate,
            'currency_id' => $currency->id,
            'created_at'  => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ]);
    }
}
