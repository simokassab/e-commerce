<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyHistory;
use App\Models\Currency;
use App\Models\Category;
use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Label;
use App\Models\Brand;
use App\Models\Price;
use App\Models\Tag;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\TaxComponent;

use COM;

class testController extends Controller
{
    //
    public function index(){
         $product = Product::find(13);

         return $product->parent;
    }

}
