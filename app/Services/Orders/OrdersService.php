<?php

namespace App\Services\Orders;


use App\Models\Coupons\Coupon;
use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;

class OrdersService {
    /**
     * @param $products
     * @param Order $order
     * @return void
     */
    public static function calculateTotalOrderPrice($products = [], Order &$order): void
    {
        $total = 0;
        $totalTax = 0;
        $productsOrders = [];
        foreach ($products as $key => $product){
            $productsOrders[$key]['order_id'] = $order->id;
            $productsOrders[$key]['product_id'] = $product['id'];
            $productsOrders[$key]['quantity'] = $product['quantity'];
            $productsOrders[$key]['unit_price'] = $product['price'];
            $productsOrders[$key]['tax_percentage'] = $product['tax_percentage'];
            $productsOrders[$key]['tax_amount'] = $product['tax'];
            $productsOrders[$key]['total'] = $product['price'] * $product['quantity'];

            $productsOrders[$key]['created_at'] = now();
            $productsOrders[$key]['updated_at'] = now();
            $total += $productsOrders[$key]['total'];
            $totalTax += $productsOrders[$key]['tax_amount'];
        }
        OrderProduct::insert($productsOrders);

        $coupon = Coupon::find($order->coupon_id ?? 0);

        if(!is_null($coupon)){
        $order->discount_percentage = $coupon->discount_percentage;
        $order->discount_amount = $coupon->discount_amount;
        }

        $order->total = $total;
        $order->tax_total = $totalTax;
    }

}




