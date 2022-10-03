<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Brand\Brand;
use App\Models\Country\Country;
use App\Models\Currency\Currency;
use App\Models\Orders\Order;
use App\Models\Product\Product;
use App\Models\Tax\Tax;
use App\Models\User\Customer;
use Illuminate\Http\Request;

class DashboardController extends MainController
{
    public function home(){
        return $this->successResponse(data:[
            'products' => Product::query()->count(),
            'countries' => Country::query()->count(),
            'orders' => Order::query()->count(),
            'taxes' => Tax::query()->count(),
            'brands' => Brand::query()->count(),
            'currencies' => Currency::query()->count(),
            'customers' => Customer::query()->count(),
        ]);
    }
}
