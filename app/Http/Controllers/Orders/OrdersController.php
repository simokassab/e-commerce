<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\MainController;
use App\Http\Resources\Country\SelectContryResource;
use App\Http\Resources\Customers\SelectCustomerResource;
use App\Http\Resources\Orders\SingelOrdersResource;
use App\Models\Country\Country;
use App\Models\Currency\CurrencyHistory;
use App\Models\Orders\OrderStatus;
use App\Models\Price\Price;
use App\Models\User\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Orders\SelectOrderStatus;
use App\Models\Orders\Order;
use App\Services\Orders\OrdersService;
use Illuminate\Support\Facades\DB;

class OrdersController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return $this->successResponse(data:[
            'countries' => SelectContryResource::collection(Country::query()->select(['id','name','iso_code_1'])->get()),
            'statuses' => SelectOrderStatus::collection(OrderStatus::query()->select(['id','name'],)->get()),
            'customers' => SelectCustomerResource::collection(Customer::query()->select(['id','first_name','last_name','phone'])->WhereNot('is_blacklist',1)->get()),
            'order' => null,
            'delivery_methods' => [
                [
                    'id' => 1,
                    'value' => 'ARMX'
                ],
                [
                    'id' => 2,
                    'value' => 'AZZAM DELIVERY'
                ],
                [
                    'id' => 3,
                    'value' => 'Mohsseeeeeeeen'
                ],
                [
                'id' => 5,
                'value' => 'Take Me'
            ],
            ],
            'payment_methods' =>[
                [
                    'id' => 1,
                    'value' => 'Cache On Delivery'
                ],
                [
                    'id' => 2,
                    'value' => 'Visa Card'
                ],
                [
                    'id' => 3,
                    'value' => 'Master Card'
                ],
                [
                    'id' => 5,
                    'value' => 'OMT Card'
                ],
            ]
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->customer_id = $request->client_id;
//            $order->time = Carbon::now()->format('H:i:s');
            $order->time = $request->time;
            $order->customer_comment = $request->customer_comment;
            $order->order_status_id = $request->order_status_id;
            $order->prefix = $request->prefix;
            $order->currency_rate = CurrencyHistory::query()->where('currency_id',1)->latest()->first()->rate;
            $order->coupon_id = $request->coupon_id;
            $products = $request->selected_products;
            $order->shipping_first_name = $request->shipping['shipping_first_name'];
            $order->shipping_last_name = $request->shipping['shipping_last_name'];
            $order->shipping_address_one = $request->shipping['shipping_address_one'];
            $order->shipping_address_two = $request->shipping['shipping_address_two'];
            $order->shipping_country_id = $request->shipping['shipping_country_id'];
            $order->shipping_email = $request->shipping['shipping_email'];
            $order->shipping_phone_number = $request->shipping['shipping_phone_number'];
            $order->payment_method_id = $request->shipping['payment_method_id'];


            $order->billing_first_name = $request->billing['billing_first_name'];
            $order->billing_last_name = $request->billing['billing_last_name'];
            $order->billing_address_one = $request->billing['billing_address_one'];
            $order->billing_address_two = $request->billing['billing_address_two'];
            $order->billing_city = $request->billing['billing_city'];
            $order->billing_country_id = $request->billing['billing_country_id'];
            $order->billing_email = $request->billing['billing_email'];
            $order->billing_phone_number = $request->billing['billing_phone_number'];
            $order->billing_first_name = $request->billing['billing_first_name'];
            $order->billing_customer_notes = $request->billing['billing_customer_notes'];

            $order->save();

            OrdersService::calculateTotalOrderPrice($products,$order);
            $order->save();

            DB::commit();

            return $this->successResponse('The order has been created successfully !', [
                'order' => new SingelOrdersResource($order->load(['status','coupon','products']))
            ]);

        }catch (\Exception $exception){
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: '. $exception);
        }catch (\Error $error){
            DB::rollBack();
            return $this->errorResponse('The Order has not been created successfully!' . 'error message: '. $error);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
