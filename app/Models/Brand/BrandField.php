<?php

namespace App\Models\Brand;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BrandField extends Model
{
    use HasFactory,HasTranslations;
    protected $table = 'brands_fields';
    protected $fillable = ['brand_id','field_id','field_value_id'];

}
