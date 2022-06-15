<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use Spatie\Translatable\HasTranslations;

class Setting extends MainModel
{
    use HasFactory,HasTranslations;

    protected $translatable=['key','value'];
    protected $fillable = ['key','value','is_developer'];
}
