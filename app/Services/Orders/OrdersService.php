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
use phpDocumentor\Reflection\DocBlock\Tags\Method;

class OrdersService {
    /**
     * @param array $productsOfOrder
     * @param Order $order
     * @return array
     */
    public static function calculateTotalOrderPrice(array $productsOfOrder = [], Order &$order): array
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

        $order->discount_percentage = $coupon?->discount_percentage;
        $order->discount_amount = $coupon?->discount_amount;

        if(!is_null($coupon)){
            if($coupon->is_one_time && $coupon->is_used){

                $order->discount_percentage = null;
                $order->discount_amount = null;
                $order->coupon_id = null;

            }
        }

        //@TODO: finish the request class
        //@TODO: make an observer for the product once saved check the coupon and change it to is_used for used ones


        $order->total = $total;
        $order->tax_total = $totalTax;

        return $productsOrders;
    }

    public static function generateOrderProducts($productsOrders,$allProducts,$defaultPricingClass,$allTaxComponents,$allTaxes,$defaultCurrency){
        $selectedProducts = [];

        foreach ($productsOrders as $key => $orderProduct) {

            $currentProduct = collect($allProducts)->where('id' , $orderProduct['product_id'])->first();
            $pricePerUnit = collect($currentProduct['prices_list'])->where('price_id' , $defaultPricingClass)->first()['price'];
            $taxPerUnit = 0;

            $selectedProducts[$key]['id'] = $orderProduct['product_id'];
            $selectedProducts[$key]['name'] = $currentProduct['name']['en'];
            if($currentProduct['tax']['is_complex']){
                $newTax= new Tax($currentProduct['tax']);
                $taxPerUnit = $newTax->getComplexPrice($pricePerUnit,$allTaxComponents->toArray(),$allTaxes->toArray());
            }else{
                $taxPerUnit = ($currentProduct['tax']['percentage'] * $pricePerUnit)/100;
            }
            $selectedProducts[$key]['tax'] = $taxPerUnit;
            $selectedProducts[$key]['image'] = $currentProduct['image'] ?? 'default_image';
            $selectedProducts[$key]['price'] = $currentProduct['quantity'] * $pricePerUnit * $taxPerUnit;
            $selectedProducts[$key]['sku'] = $currentProduct['sku'];
            $selectedProducts[$key]['quantity'] = $orderProduct['quantity'];
            $selectedProducts[$key]['quantity_in_stock_available'] = $currentProduct['minimum_quantity'] < 0 ? 0 : $currentProduct['quantity'] - $currentProduct['minimum_quantity'];
            $selectedProducts[$key]['quantity_in_stock'] = $currentProduct['quantity'];
            $selectedProducts[$key]['currency']  = $defaultCurrency->symbol;
        }

        return $selectedProducts;

    }

    public static function adjustQuantityOfOrderProducts($orderProducts): void
    {
        foreach ($orderProducts as $orderProduct){
            echo $orderProduct['quantity'];
            $product = Product::find($orderProduct['id'])->updateProductQuantity($orderProduct['quantity'],'sub');

        }
        die();
    }
}




