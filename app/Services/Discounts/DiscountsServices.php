<?php

namespace App\Services\Discounts;


class DiscountsServices {

    /**
     * @param $arrays
     * @return array
     */
    public static function mergeAllProducts(Array $arrays): array
    {
        $products = [];
        foreach($arrays as $productsArray){
            foreach ($productsArray as $product){
                $products[] =  $product;
            }
        }

        return $products;

    }
}




