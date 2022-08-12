<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends MainRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "client_id" => 'required|exists:customers,id',
//            "time" => "required|date_format:H:i",
//            "date" => "required|date_format:format",
            "comment" => "required",
            "status_id" => 'required|exists:order_statuses,id',
            "shipping_as_billing" => 'required|boolean',
            "coupon_code" => "exists:coupons,code",

            "selected_products.*.id" => 'required|exists:products',
            "selected_products.*.quantity" => 'required|integer',

            "billing.first_name" => 'required|string',
            "billing.last_name" => 'required|string',
            "billing.company_name" => 'nullable|string',
            "billing.address_1" => 'required|string',
            "billing.address_2" => 'required|string',
            "billing.city" => 'required|string',
            "billing.country_id" => 'required|exists:countries,id',
            "billing.phone_number" => 'required|integer',
            "billing.email_address" => 'required|email',
            "billing.payment_method_id" => 'required|exists:payments_types,id',

            "shipping.first_name" => 'required|string',
            "shipping.last_name" => 'required|string',
            "shipping.company_name" => 'nullable|string',
            "shipping.address_1" => 'required|string',
            "shipping.address_2" => 'required|string',
            "shipping.city" => 'required|string',
            "shipping.country_id" => 'required|exists:countries,id',
            "shipping.phone_number" => 'required|integer',
            "shipping.email_address" => 'required|email',

        ];
    }
}
