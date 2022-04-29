<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CurrencyHistory;

class Currency extends Model
{
    use HasFactory;
    protected $table='currencies';

    public function currencyHistory(){
        return $this->hasMany(CurrencyHistory::class,'currency_id','id');
    }
}
