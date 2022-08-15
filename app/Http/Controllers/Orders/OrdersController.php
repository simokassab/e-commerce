<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\MainController;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\Country\SelectContryResource;
use App\Http\Resources\Customers\SelectCustomerResource;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\SingelOrdersResource;
use App\Http\Resources\roles\RolesResource;
use App\Models\Country\Country;
use App\Models\Coupons\Coupon;
use App\Models\Currency\Currency;
use App\Models\Currency\CurrencyHistory;
use App\Models\Orders\OrderStatus;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\RolesAndPermissions\CustomRole;
use App\Models\Settings\Setting;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use App\Models\User\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Orders\SelectOrderStatus;
use App\Models\Orders\Order;
use App\Services\Orders\OrdersService;
use Illuminate\Support\Facades\DB;

class OrdersController extends MainController
{
    const OBJECT_NAME = 'objects.role';
    const relations =['customer'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method()=='POST') {
            $searchKeys=['code','time','date','total'];
            $searchRelationsKeys = ['customer' => ['customer_first_name' => 'first_name', 'customer_last_name' => 'last_name'] ];
            return $this->getSearchPaginated(OrderResource::class, Order::class,$request, $searchKeys,self::relations,$searchRelationsKeys);
        }

        return $this->successResponsePaginated(OrderResource::class,Order::class,self::relations);
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
            'currencies' => ::collection(Country::query()->select(['id','name','iso_code_1'])->get()),
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
    public function store(StoreOrderRequest $request)
    {
        DB::enableQueryLog();
        $allProducts = Product::with(['tax','pricesList'])->get()->toArray();
        $defaultPricingClass = Setting::where('title','website_pricing')->first()->value;
        $allTaxes = Tax::all();
        $allTaxComponents = TaxComponent::all();
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->customer_id = $request->client_id;
//            $order->time = Carbon::now()->format('H:i:s');
            $order->time = $request->time;
            $order->customer_comment = $request->comment;
            $order->order_status_id = $request->status_id;
            $defaultCurrency = Currency::where('is_default',1)->first();
            if(is_null($defaultCurrency)){
                throw new \Exception();
            }
            $order->currency_rate = CurrencyHistory::query()->where('currency_id',1)->latest()->first()->rate;

            $coupon = Coupon::where('code', $request->coupon_code)->first();

            $order->coupon_id =  $coupon ? $coupon->id : 0;
            $products = $request->selected_products;
            $order->shipping_first_name = $request->shipping['first_name'];
            $order->shipping_last_name = $request->shipping['last_name'];
            $order->shipping_address_one = $request->shipping['address_1'];
            $order->shipping_address_two = $request->shipping['address_2'];
            $order->shipping_country_id = $request->shipping['country_id'];
            $order->shipping_city = $request->shipping['city'];
            $order->shipping_company_name = $request->shipping['company_name'];
            $order->shipping_email = $request->shipping['email_address'];
            $order->shipping_phone_number = $request->shipping['phone_number'];
            $order->prefix ='0';


            $order->billing_first_name = $request->billing['first_name'];
            $order->billing_last_name = $request->billing['last_name'];
            $order->billing_company_name = $request->billing['company_name'];
            $order->billing_address_one = $request->billing['address_1'];
            $order->billing_address_two = $request->billing['address_2'];
            $order->billing_city = $request->billing['city'];
            $order->billing_country_id = $request->billing['country_id'];
            $order->billing_email = $request->billing['email_address'];
            $order->billing_phone_number = $request->billing['phone_number'];
            $order->payment_method_id = $request->billing['payment_method_id'];

            $order->save();
            $order->prefix = 'order' . $order->id;

            $productsOrders = OrdersService::calculateTotalOrderPrice($products,$order);
            $order->save();


            $order->selected_products = OrdersService::generateOrderProducts($productsOrders,$allProducts,$defaultPricingClass,$allTaxComponents,$allTaxes,$defaultCurrency);

//            return ($productsOrders);
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

    public function getTableHeaders(){
        return $this->successResponse('Success!', ['headers' => __('headers.orders') ]);
    }
}
