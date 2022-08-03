<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

class PricesListController extends MainController
{
    public function getTableHeaders(){
        return $this->successResponse('Success!', ['headers' => __('headers.prices_list') ]);
    }

    public function store(Request $request){
        
    }
}
