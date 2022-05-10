<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table='countries';

    protected $fillable=[
        'name',
        'iso_code_1',
        'iso_code_2',
        'phone_code',
        'flag'
    ];

}
