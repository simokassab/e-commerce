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
            "customer_comment" => $this->customer_comment,
            "order_status_id" => $this->order_status_id,
            "prefix" => $this->prefix,
            "coupon" => $this->coupon_id,
//            "selected_products" => ($this->whenLoaded('')),



        ];
    }
}
