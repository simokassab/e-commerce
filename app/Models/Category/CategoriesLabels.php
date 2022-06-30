<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoriesLabels extends Model
{
    use HasFactory,HasTranslations;
    protected $translatable=[''];
    protected $table='categories_labels';

}
