<?php

namespace App\Models\Brand;

use App\Models\Field\Field;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BrandField extends Model
{
    use HasFactory,HasTranslations;
    protected array $translatable=[];
    protected $table = 'brands_fields';
    protected $fillable = ['brand_id','field_id','field_value_id'];

    public function field(){
        return $this->hasOne(Field::class,'id','field_id');
    }


}
