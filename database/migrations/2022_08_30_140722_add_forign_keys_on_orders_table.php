<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->foreign('customer_id')->on('customers')->references('id');
//            $table->foreign('coupon_id')->on('coupons')->references('id');
            $table->foreign('order_status_id')->on('order_statuses')->references('id');
            $table->foreign('shipping_country_id')->on('countries')->references('id');
            $table->foreign('billing_country_id')->on('countries')->references('id');
//            $table->foreign('shipping_address_id')->on('customer_address')->references('id');
//            $table->foreign('billing_address_id')->on('customer_address')->references('id');
//            $table->foreign('currency_id')->on('currencies')->references('id');
            $table->foreign('payment_method_id')->on('payments_types')->references('id');


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_customer_id_foreign');
//            $table->dropForeign('coupon_id_foreign');
            $table->dropForeign('orders_order_status_id_foreign');
            $table->dropForeign('orders_shipping_country_id_foreign');
            $table->dropForeign('orders_billing_country_id_foreign');
//            $table->dropForeign('shipping_address_id_foreign');
//            $table->dropForeign('billing_address_id_foreign');
//            $table->dropForeign('currency_id_foreign');
            $table->dropForeign('orders_payment_method_id_foreign');

        });
    }
};
