<?php

namespace App\Services\Orders;


use App\Models\Orders\Order;

class OrdersService {
    /**
     * @param $products
     * @param Order $order
     * @return void
     */
    public static function calculateTotalOrderPrice($products = [], Order &$order): void
    {
        $productsOrders = [];
        foreach ($products as $key => $product){
            $productsOrders[$key]['order_id'] = $order->id;
            $productsOrders[$key]['product_id'] = $product['id'];
            $productsOrders[$key]['quantity'] = $product['quantity'];
            $productsOrders[$key]['unit_price'] = $product['price'];

            $productsOrders[$key]['tax_percentage'] = $product['tax_percentage'];

            $productsOrders[$key]['tax_amount'] = $product['tax'];
            $productsOrders[$key]['total'] = $product['price'] * $product['quantity'];

        }
        dd($productsOrders);
    }

}




