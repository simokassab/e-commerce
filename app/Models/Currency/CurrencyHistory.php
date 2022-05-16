<?php



namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Currency\Currency;

class CurrencyHistory extends MainModel
{
    use HasFactory;
    protected $table='currencies_histories';
    protected $fillable=['currency_id','rate'];
    protected $guard_name = 'sanctum';


    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
}
