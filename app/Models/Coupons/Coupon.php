<?php

namespace App\Models\Coupons;

use App\Models\Settings\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Coupon extends Model
{
    use HasFactory;

    public function checkIfCouponIsValid($amount = null) : array{

//        $isDiscountOnShipping = Setting::where('title','is_discount_on_shipping')->first()->value;
        $isDiscountOnShipping = Cache::get('settings')->where('title','is_discount_on_shipping')->first()->value;

        if($this->is_one_time && $this->is_used){
            return [
                'is_valid' => false,
                'error_message' => 'Sorry, but this coupon was used',
                'percentage' => $this->discount_percentage,
                'amount' => $this->discount_amount,
                'minimum_amount' => $this->min_amount,
                'is_discount_on_shipping' => (boolean)$isDiscountOnShipping

            ];
        }

        if($this->expiry_date < now() && !is_null($this->expiry_date)){
            return [
                'is_valid' => false,
                'error_message' => 'Sorry, but this coupon has expired at '.$this->expiry_date,
                'percentage' => $this->discount_percentage,
                'amount' => $this->discount_amount,
                'minimum_amount' => $this->min_amount,
                'is_discount_on_shipping' => (boolean)$isDiscountOnShipping


            ];
        }
        if($this->min_amount > $amount && !is_null($this->min_amount)){
            return [
                'is_valid' => false,
                'error_message' => 'Sorry, but you at least have to buy ' . $this->min_amount . ' to use this coupon',
                'percentage' => $this->discount_percentage,
                'amount' => $this->discount_amount,
                'minimum_amount' => $this->min_amount,
                'is_discount_on_shipping' => (boolean)$isDiscountOnShipping

            ];
        }
        return [
            'is_valid' => true,
            'message' => 'you have received an discount of ' . $this->discount_percentage ?? $this->discount_amount,
            'error_message' => '',
            'amount' => $this->discount_amount,
            'percentage' => $this->discount_percentage,
            'minimum_amount' => $this->min_amount,
            'is_discount_on_shipping' => (boolean)$isDiscountOnShipping


        ];

    }
}
