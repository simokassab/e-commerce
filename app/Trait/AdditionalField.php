<?php
namespace App\Trait;

use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait AdditionalField
{
public function storeUpdateFields(array $fields,Model $model,$oldFields = [],Product|Category|Brand $entity){

}

}
