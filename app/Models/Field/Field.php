<?php

namespace App\Models\Field;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Field\FieldValue;
use App\Models\Category\Category;
use App\Models\Brand\Brand;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Field extends MainModel
{
    use HasFactory, HasTranslations;

    protected array $translatable = ['title'];
    protected $fillable=['title','type','entity','is_required','is_attribute'];
    protected $table = 'fields';
    // array since I have used it product service and I need it to be array
    public static $fieldTypes = ['checkbox', 'text', 'select', 'textarea', 'date','multi-select'];
    public static $entities = 'category,product,brand';

    public function fieldValue(): HasMany
    {
        return $this->hasMany(FieldValue::class, 'field_id','id');
    }
    public function category()
    {
        return $this->belongsToMany(Category::class, 'categories_fields', 'field_id', 'category_id')->whereModel(Category::class);
    }
    public function fieldValueCategoire(): BelongsToMany
    {
        return $this->belongsToMany(FieldValue::class, 'categories_fields', 'field_id');
    }


    public function brand(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brands_fields', 'field_id', 'brand_id');
    }
    public function fieldValueBrand(): BelongsToMany
    {
        return $this->belongsToMany(FieldValue::class, 'brands_fields', 'field_id');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'id','product_id');
    }
}
