<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\MainRequest;
use App\Models\Product\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreOrderRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $products = $request->selected_products ?? [];

//        foreach ($products as $key => $product){
//            $productObject = Product::query()->select(['id','quantity','minimum_quantity'])->find($product['id']);
//            $productsRules['selected_products.'.$key.'.quantity'] ='required|integer|min:1|lte:'.$productObject['quantity'] - $productObject['minimum_quantity'];
//
//        }

        return [
            "client_id" => 'required|exists:customers,id',
            'currency_id' => 'required|numeric|exists:currencies,id',
//            "time" => "required|date_format:H:i",
//            "date" => "required|date_format:format",

            "comment" => "nullable",
            "notes" => 'nullable|array',
            "notes.*.user_id" => 'nullable',
            "notes.*.title" => 'nullable',
            "notes.*.body" => 'nullable',

            "status_id" => 'required|exists:order_statuses,id',
            "is_billing_as_shipping" => 'required|boolean',
            "coupon_code" => "nullable|exists:coupons,code",

            "selected_products" => 'required|array',
            "selected_products.*" => 'required',
            "selected_products.*.id" => 'required|exists:products',
            "selected_products.*.quantity" => 'required|numeric',
            "selected_products.*.unit_price" => 'nullable|numeric',

            "billing.first_name" => 'required|string',
            "billing.last_name" => 'required|string',
            "billing.company_name" => 'nullable|string',
            "billing.address_1" => 'required|string',
            "billing.address_2" => 'required|string',
            "billing.city" => 'required|string',
            "billing.country_id" => 'required|exists:countries,id',
            "billing.phone_number" => 'required|numeric',
            "billing.email_address" => 'required|email',
//            "billing.payment_method_id" => 'required|exists:payments_types,id',
            "billing.payment_method_id" => 'required|numeric',

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

        // return(array_merge($rules,$productsRules));
    }

    public function messages()
    {

        return [
            'client_id.required' => 'the :attribute field is required',
            'comment.required' => 'the :attribute field is required',
            'status_id.required' => 'the :attribute field is required',
            'coupon_code.required' => 'The :attribute field is required',
            'selected_products.required' => 'The :attribute are required',
            'selected_products.*.id' => 'The product id is required',
            'selected_products.*.quantity' => 'The product quantity is required',




        ];

    }
}
