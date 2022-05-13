<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tax\Tax;

class TaxComponent extends Model
{
    use HasFactory;
    protected $table='taxes_components';
    protected $guard_name = 'sanctum';

    public function tax(){
        return $this->belongsTo(Tax::class,'tax_id');
    }
    public function taxChilds(){
        return $this->belongsTo(Tax::class,'component_tax_id');
    }
}
