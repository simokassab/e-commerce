<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use Spatie\Translatable\HasTranslations;

class Country extends MainModel
{
    use HasFactory, HasTranslations;

    protected $translatable = ['name'];
    protected $table = 'countries';
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'iso_code_1',
        'iso_code_2',
        'phone_code',
        'flag'
    ];

    public static $imagesPath = [
        'images' => 'countries/images',
    ];
}
