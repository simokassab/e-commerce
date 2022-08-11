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

            "client_id" => $this->customer_id,
            "time" => $this->time,
            "date" => $this->date,
            "comment" => $this->customer_comment,
            "status_id" => $this->order_status_id,
            "prefix" => $this->prefix,
            "coupon_code" => $this->whenLoaded('coupon') ? $this->whenLoaded('coupon')->code : '',
            "selected_products" => ($this->whenLoaded('products')),
            "billing" => [
                "first_name" => "mohammad",
                "last_name" => "azzam",
                "company_name" => "MTX",
                "address_1" => "hellaye",
                "address_2" => "abra",
                "city" => "Saida",
                "country_id" => 1,
                "phone_number" => "96176023035",
                "email_address" => "azzam@gmail.com",
                "payment_method_id" => 1
            ],
            "shipping" => [
                "first_name" => "mohammad",
                "last_name" => "azzam",
                "company_name" => "MTX",
                "address_1" => "hellaye",
                "address_2" => "abra",
                "city" => "Saida",
                "country_id" => 1,
                "phone_number" => "96176023035",
                "email_address" => "azzam@gmail.com"
            ]



        ];
    }
}
