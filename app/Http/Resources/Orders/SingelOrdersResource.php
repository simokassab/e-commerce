<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class SingelOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'code' => $this->id,
            "client_id" => $this->customer_id,
            "time" => $this->time,
            "date" => $this->date,
            "comment" => $this->customer_comment,
            "status_id" => $this->order_status_id,
            "prefix" => $this->prefix,
            "coupon_code" => $this->whenLoaded('coupon') ? $this->whenLoaded('coupon')->code : '',
            "billing" => [
                "first_name" => $this->billing_first_name,
                "last_name" => $this->billing_last_name,
                "company_name" => $this->billing_company_name,
                "address_1" => $this->billing_address_one,
                "address_2" => $this->billing_address_two,
                "city" => $this->billing_city,
                "country_id" => $this->billing_country_id,
                "phone_number" => $this->billing_phone_number,
                "email_address" => $this->billing_email,
                "payment_method_id" => $this->payment_method_id
            ],
            "shipping" => [
                "first_name" => $this->shipping_first_name,
                "last_name" => $this->shipping_last_name,
                "company_name" => $this->shipping_company_name,
                "address_1" => $this->shipping_address_one,
                "address_2" => $this->shipping_address_two,
                "city" => $this->shipping_city,
                "country_id" => $this->shipping_country_id,
                "phone_number" => $this->shipping_phone_number,
                "email_address" => $this->shipping_email
            ],
//            "selected_products" => [
//                "id" => 1,
//                "name"=> "sfsdfdsfsdfcs",
//                "tax"=> 110,
//                "image"=> "default_image",
//                "sku"=> "asdasd",
//                "price"=> 610,
//                "currency"=> "$",
//                "quantity"=> 5,
//                "quantity_in_stock_available"=> 1,
//                "quantity_in_stock"=> 1,
//                "tax_percentage" => 20
//
//            ]

            'selected_products' => $this->selected_products,

        ];
    }
}
