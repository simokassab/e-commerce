<?php

namespace App\Models\Orders;

use App\Models\Coupons\Coupon;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function status(){
        return $this->hasOne(OrderStatus::class,'id','order_status_id');
    }

    public function coupon(){
        return $this->hasOne(Coupon::class,'id','coupon_id');
    }

    public function products(){
        return $this->BelongsToMany(Product::class,'order_products','order_id','product_id');
    }


}
