<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;

class Country extends MainModel
{
    use HasFactory;
    protected $table='countries';
    protected $guard_name = 'sanctum';

    protected $fillable=[
        'name',
        'iso_code_1',
        'iso_code_2',
        'phone_code',
        'flag'
    ];

}
