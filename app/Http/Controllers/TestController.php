<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Label\LabelController;
use App\Models\Brand\Brand;
use App\Models\Country\Country;
use App\Models\Currency\Currency;
use App\Models\Label\Label;
use App\Models\Product\Product;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\Tax\Tax;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\MainController;
use App\Exceptions\FileErrorException;
use App\Models\Product\ProductField;
use App\Models\RolesAndPermissions\CustomPermission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use App\Actions\Taxses\CalculateTax;
use App\Models\Category\Category;

class TestController extends MainController
{
    use HasRoles;
    public function __construct()
    {
        $this->map_permissions = [];
        parent::__construct($this->map_permissions);
    }

    public function getToken()
    {
        return User::first()->createToken('my-token');
    }

    public function test()
    {

        // dd($this->imagesPath['images']);

<<<<<<< HEAD
        // return Product::query()->find(1)->updateProductQuantity(5, 'sub');
        //        return Product::query()->find(1)->relatedProducts;
=======
        return Product::find(1);
//        return Product::query()->find(1)->relatedProducts;
>>>>>>> 5c5f262b6e791986c375200cfa716f5df5f55b0f
    }
}
