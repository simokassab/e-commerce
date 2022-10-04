<?php

namespace App\Models\Brand;

use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Tax\Tax;
use App\Models\Unit\Unit;
use App\Models\MainModel;
use App\Models\Label\Label;
use App\Models\Field\Field;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Brand extends MainModel
{
    protected array $translatable = ['name', 'meta_title', 'meta_description', 'meta_keyword', 'description'];
    protected $table = 'brands';

    protected $fieldKey = 'brand_id';
    protected $fieldClass = BrandField::class;
    protected array $fieldDBColumns = ['id','value', 'field_value_id', 'field_id', 'brand_id'];

    public static array $imagesPath = [
        'images' => 'brands/images',
    ];

    public function label(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'brands_labels', 'brand_id');
    }
    public function field(): BelongsToMany
    {
        return $this->belongsToMany(field::class, 'brands_fields', 'brand_id', 'field_id');
    }
    public function fieldValue(): HasMany
    {
        return $this->hasMany(BrandField::class, 'brand_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
    public function tax(): HasMany
    {
        return $this->hasMany(Tax::class, 'tax_id');
    }
    public function unit(): HasMany
    {
        return $this->hasMany(Unit::class, 'unit_id');
    }
}
