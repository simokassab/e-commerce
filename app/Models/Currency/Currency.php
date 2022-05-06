<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Currency\CurrencyHistory;
use App\Models\Price\Price;

class Currency extends Model
{
    use HasFactory;
    protected $table='currencies';

    public function currencyHistory(){
        return $this->hasMany(CurrencyHistory::class,'currency_id');
    }
    public function price(){
        return $this->hasMany(Price::class,'currency_id');
    }
}
