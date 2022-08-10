<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\MainController;
use App\Http\Resources\Country\SelectContryResource;
use App\Http\Resources\Customers\SelectCustomerResource;
use App\Models\Country\Country;
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
//        $order->time = Carbon::now()->format('H:i:s');
        $order->time = $request->time;
        $order->customer_comment = $request->customer_comment;
        $order->order_status_id = $request->order_status_id;
        $order->prefix = $request->prefix;
        $currencyRate = Price::findOrFail($request->price_class)->currency->currencyHistory->last()->rate;
        $order->currency_rate = $currencyRate;
        $order->coupon_id = $request->coupon_id;
        $order->save();
        $products = $request->selected_products;

        $totalPrice = OrdersService::calculateTotalOrderPrice($products,$order);
        DB::commit();


        return $this->successResponse('The order has been created successfully !', [
            'order' => $order
        ]);

        }catch (\Exception $exception){
            dd($exception);
            DB::rollBack();
            return $this->errorResponse('The Order has not been created correctly!');
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
