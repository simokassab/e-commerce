<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Tax\Tax;

class TaxComponent extends MainModel
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
