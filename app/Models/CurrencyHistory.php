<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;

class CurrencyHistory extends Model
{
    use HasFactory;
    protected $table='currencies_histories';
    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
}
