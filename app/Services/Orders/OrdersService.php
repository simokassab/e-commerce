<?php

namespace App\Services\Orders;


use App\Models\Coupons\Coupon;
use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;

class OrdersService {
    /**
     * @param array $productsOfOrder
     * @param Order $order
     * @return array
     */
    public static function calculateTotalOrderPrice(array $productsOfOrder = [], Order &$order): void
    {
        $taxes = Tax::all();
        $allTaxComponents = TaxComponent::all();
        $products = Product::all();
        $prices = ProductPrice::all();

        $total = 0;
        $totalTax = 0;
        $productsOrders = [];

        foreach ($productsOfOrder as $key => $product){
            $priceOfUnit = $prices->where('product_id' , $product['id'])->where('price_id',1)->first() ? $prices->where('product_id' , $product['id'])->where('price_id',1)->first()->price : 0;
            $mainProduct = $products->where('id',$product['id'])->first();
            $taxObject = $taxes->where('id',$mainProduct->tax_id)->first();
            if($taxObject->is_complex){
                $tax = $taxObject->getComplexPrice($priceOfUnit,$allTaxComponents->toArray(),$taxes->toArray());

            }else{
                $tax = $taxObject->percentage * $priceOfUnit/100;
            }
            $productsOrders[$key]['order_id'] = $order->id;
            $productsOrders[$key]['product_id'] = $product['id'];
            $productsOrders[$key]['quantity'] = $product['quantity'];
            $productsOrders[$key]['unit_price'] = $priceOfUnit;
            $productsOrders[$key]['tax_percentage'] = $taxObject->percentage;
            $productsOrders[$key]['tax_amount'] = $tax;
            $productsOrders[$key]['total'] = $priceOfUnit * $product['quantity'];

            $productsOrders[$key]['created_at'] = now();
            $productsOrders[$key]['updated_at'] = now();
            $total += $productsOrders[$key]['total'];
            $totalTax += $tax;
        }
         OrderProduct::insert($productsOrders);

        $coupon = Coupon::query()
            ->where('id', $order->coupon_id ?? 0)
            ->first();

        $order->discount_percentage = $coupon->discount_percentage;
        $order->discount_amount = $coupon->discount_amount;

        if(!is_null($coupon)){
            if($coupon->is_one_time && $coupon->is_used){

                $order->discount_percentage = null;
                $order->discount_amount = null;
                $order->coupon_id = null;

            }
        }

        //@TODO: finish the request class
        //@TODO: finish the resource class for the product inside the order
        //@TODO: make an observer for the product once saved check the coupon and change it to is_used for used once


        $order->total = $total;
        $order->tax_total = $totalTax;
    }

}




