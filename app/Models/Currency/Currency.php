<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Currency\CurrencyHistory;
use App\Models\Price\Price;

class Currency extends MainModel
{
    use HasFactory;
    protected $table='currencies';
    protected $guard_name = 'sanctum';

    public function currencyHistory(){
        return $this->hasMany(CurrencyHistory::class,'currency_id');
    }
    public function price(){
        return $this->hasMany(Price::class,'currency_id');
    }
}
