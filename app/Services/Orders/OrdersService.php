<?php

namespace App\Services\Orders;


use App\Models\Coupons\Coupon;
use App\Models\Orders\Order;
use App\Models\Orders\OrderNote;
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
    public static function calculateTotalOrderPrice(array $productsOfOrder = [], Order $order): array
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

        $order->discount_percentage = $coupon ? $coupon->discount_percentage : 0;
        $order->discount_amount = $coupon ? $coupon->discount_amount : 0;

        if(!is_null($coupon)){
            if($coupon->is_one_time && $coupon->is_used){
                throw new \Exception('The coupon is already used!');
            }
        }

        $order->total = $total;
        $order->tax_total = $totalTax;

        return $productsOrders;
    }

    public static function generateOrderProducts($productsOrders,$allProducts,$defaultPricingClass,$allTaxComponents,$allTaxes,$defaultCurrency): array
    {
        $selectedProducts = [];
        foreach ($productsOrders as $key => $orderProduct) {
            $currentProduct = collect($allProducts)->where('id' , $orderProduct['product_id'])->first();
            if(is_null($currentProduct)){
                continue;
            }

            $pricePerUnit = collect($currentProduct['prices_list'])->where('price_id' , $defaultPricingClass)->first();
            if(is_null($pricePerUnit)){
                continue;
            }
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
            $selectedProducts[$key]['price'] = $pricePerUnit + $taxPerUnit;
            $selectedProducts[$key]['sku'] = $currentProduct['sku'];
            $selectedProducts[$key]['quantity'] = $orderProduct['quantity'];
//            $selectedProducts[$key]['quantity_in_stock_available'] = $currentProduct['minimum_quantity'] < 0 ? 0 : $currentProduct['quantity'] - $currentProduct['minimum_quantity'];
            $selectedProducts[$key]['quantity_in_stock'] = $currentProduct['quantity'];
            $selectedProducts[$key]['currency_symbol']  = $defaultCurrency?->symbol;
            $selectedProducts[$key]['type']  = $currentProduct['type'];
            $selectedProducts[$key]['edit_status']  = false;
            $selectedProducts[$key]['pre_order']  = $currentProduct['pre_order'];

        }

        return $selectedProducts;

    }

    public static function adjustQuantityOfOrderProducts($orderProducts): void
    {
        foreach ($orderProducts as $orderProduct){
            Product::find($orderProduct['id'])->updateProductQuantity($orderProduct['quantity'],'sub');

        }

    }

    public static function updateProductsOfOrder( $order,array $newProducts,array $oldOrderProducts,array $allProducts = [], array $allOrdersProducts): void
    {
        // old products from database
        // new products from request
        // oldOrderProducts are the relation of each product
        // all the products in the database

        $oldProductsWithQuantities = collect($oldOrderProducts)->pluck('quantity','product_id')->all();
        $newProductsWithQuantities = collect($newProducts)->pluck('quantity','id')->all();

//        $newAddedProducts = [];
//        $oldUpdatedProducts = [];
        $deletedProducts = [];
//`
////        $arraysLoop = count($oldProductsWithQuantities) >= count($newProductsWithQuantities) ? ($oldProductsWithQuantities) : ($newProductsWithQuantities);
//
//        foreach ($newProductsWithQuantities as $key => $newItem) {
//            if(array_key_exists($key,$oldProductsWithQuantities)){
//                $oldUpdatedProducts[$key] = $newItem;
//            }else{
//                $newAddedProducts[$key] = $newItem;
//            }
//        }

        foreach ($oldProductsWithQuantities as $key => $oldItem) {
            if(!array_key_exists($key,$newProductsWithQuantities)){
                $deletedProducts[$key] = $oldItem;
            }
        }

        $taxes = Tax::all();
        $allTaxComponents = TaxComponent::all();
        $products = Product::all();
        $prices = ProductPrice::all();
        $total = 0;
        $totalTax = 0;


        //prepare the array for update
        $dataToBeUpdatedOrCreated = [];
        foreach ($newProducts as $key => $product){
            $priceOfUnit = $prices->where('product_id' , $product['id'])->where('price_id',1)->first() ? $prices->where('product_id' , $product['id'])->where('price_id',1)->first()->price : 0;
            $mainProduct = $products->where('id',$product['id'])->first();
            $taxObject = $taxes->where('id',$mainProduct->tax_id)->first();
            $oldOrderProduct = collect($allOrdersProducts)->where('product_id',$product['id'])->where('order_id',$order->id)->first();
            $mainProduct->updateProductQuantity($product['quantity'],'sub');
            if($taxObject->is_complex){
                $tax = $taxObject->getComplexPrice($priceOfUnit,$allTaxComponents->toArray(),$taxes->toArray());

            }else{
                $tax = $taxObject->percentage * $priceOfUnit/100;
            }
            $dataToBeUpdatedOrCreated[$key]['id'] = $oldOrderProduct ? $oldOrderProduct['id'] : null ;
            $dataToBeUpdatedOrCreated[$key]['order_id'] = $order->id;
            $dataToBeUpdatedOrCreated[$key]['product_id'] = $product['id'];
            $dataToBeUpdatedOrCreated[$key]['quantity'] = $product['quantity'];
            $dataToBeUpdatedOrCreated[$key]['unit_price'] = $priceOfUnit;
            $dataToBeUpdatedOrCreated[$key]['tax_percentage'] = $taxObject->percentage;
            $dataToBeUpdatedOrCreated[$key]['tax_amount'] = $tax;
            $dataToBeUpdatedOrCreated[$key]['total'] = $priceOfUnit * $product['quantity'];

            $dataToBeUpdatedOrCreated[$key]['created_at'] = now();
            $dataToBeUpdatedOrCreated[$key]['updated_at'] = now();
            $total += $dataToBeUpdatedOrCreated[$key]['total'];
            $totalTax += $tax;
        }
        // update or create the new products of the order
        OrderProduct::upsert($dataToBeUpdatedOrCreated,['id'],['quantity','unit_price','tax_percentage','tax_amount','total','created_at','updated_at']);

        //delete non used products in the order
        OrderProduct::query()
            ->whereIn('product_id',array_keys($deletedProducts))
            ->where('order_id',$order->id)
            ->delete();

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

        // now add the old quantity to the database
        foreach ($deletedProducts as $key => $deletedProductQuantity){
            Product::find($key)->updateProductQuantity($deletedProductQuantity, "add");
        }

        $order->total = $total;
        $order->tax_total = $totalTax;

    }

    public static function createNotesForOrder(Order $order, array $notes,array $data= []):bool{

        $notesToBeSaved = [];
        foreach ($notes as $key => $note) {
            $notesToBeSaved[$key]['user_id'] = auth()->user()->id;
            $notesToBeSaved[$key]['title'] = $note['title'];
            $notesToBeSaved[$key]['body'] = $note['body'];
            $notesToBeSaved[$key]['date'] = now();
            $notesToBeSaved[$key]['created_at'] = now();
            $notesToBeSaved[$key]['updated_at'] = now();
            $notesToBeSaved[$key]['order_id'] = $order->id;
            $notesToBeSaved[$key]['order_status_id'] = array_key_exists('order_status_id',$data) ? $data['order_status_id'] : null ;

        }

        return OrderNote::insert($notesToBeSaved);
    }

    public static function updateNotesForOrder(Order $order, array $newNotes,array $data= []){
        $oldNotesCollection = collect($order->notes);
        $newNotes = collect($newNotes);

        $oldNotesToBeDeleted = $oldNotesCollection->filter(function ($object)use($newNotes){
           return (!$newNotes->contains('id',$object->id));
        });

        OrderNote::query()->whereIn('id',$oldNotesToBeDeleted->pluck('id'))->whereNull('order_status_id')->delete();

        $notesToBeSaved = [];
        foreach ($newNotes as $key => $note) {
            $notesToBeSaved[$key]['id'] = $note['id'] ?? null;
            $notesToBeSaved[$key]['user_id'] = auth()->user()->id;
            $notesToBeSaved[$key]['title'] = $note['title'];
            $notesToBeSaved[$key]['body'] = $note['body'];
            $notesToBeSaved[$key]['date'] = now();
            $notesToBeSaved[$key]['created_at'] = $note['title'];
            $notesToBeSaved[$key]['updated_at'] = now();
            $notesToBeSaved[$key]['order_id'] = $order->id;
            $notesToBeSaved[$key]['order_status_id'] =  null ;
        }

        OrderNote::query()->upsert($notesToBeSaved,['id'],['user_id','customer_id','order_id','order_status_id','title','body','date','created_at','updated_at']);

    }
}




