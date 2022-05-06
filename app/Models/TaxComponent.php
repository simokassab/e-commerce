<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tax;

class TaxComponent extends Model
{
    use HasFactory;
    protected $table='taxes_components';

    public function tax(){
        return $this->belongsTo(Tax::class,'tax_id');
    }
    public function taxChilds(){
        return $this->belongsTo(Tax::class,'component_tax_id');
    }
}
