<?php

namespace App\Models\Coupons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function checkIfCouponIsValid($amount) : array{

        if($this->min_amount > $amount){
            return [
                'color' => 'red',
                'message' => 'Sorry, but you at least have to buy ' . $this->min_amount . ' to use this coupon',
                'percentage' => null,
            ];
        }

        if($this->is_one_time && $this->is_used){
            return [
                'color' => 'red',
                'message' => 'Sorry, but this coupon is in use',
                'percentage' => null,
            ];
        }

        if($this->expiry_date < now()){
            return [
                'color' => 'red',
                'message' => 'Sorry, but this coupon has expired at '.$this->expiry_date,
                'percentage' => null,
            ];
        }
        return [
            'color' => 'red',
            'message' => 'Sorry, but this coupon has expired at '.$this->expiry_date,
            'percentage' => null,
        ];

    }
}
