<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use Spatie\Translatable\HasTranslations;

class Setting extends MainModel
{
    use HasFactory,HasTranslations;

    protected $translatable=['value'];
    protected $fillable = ['title','value','is_developer'];

}
