<?php

namespace App\Models;

use App\Trait\AdditionalField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

abstract class MainModel extends Model
{
    use HasFactory, HasTranslations, AdditionalField;

    protected $guard_name = 'web';


    public function setIsDefault()
    {
        $this->query()
            ->where('is_default', true)
            ->whereNot('id', $this->id)
            ->update(['is_default' => false]);

        $this->is_default = true;

        return $this;
    }

    public static function getMaxSortValue()
    {
        return self::max('sort') + 1;// get the max sort and add one to it
    }

    public function scopeOrder($query)
    {
        $query->orderByRaw('ISNULL(sort), sort ASC');
    }


    // a new function that is called on an objec and retuens the translated value of the field
    // it will paginate the data and return it with translations


}
