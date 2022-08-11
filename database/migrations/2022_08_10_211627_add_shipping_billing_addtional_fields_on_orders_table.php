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

            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->text('shipping_address_one')->nullable();
            $table->text('shipping_address_two')->nullable();
            $table->string('shipping_city')->nullable();
            $table->unsignedBigInteger('shipping_country_id')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone_number')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();

            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->text('billing_address_one')->nullable();
            $table->text('billing_address_two')->nullable();
            $table->string('billing_city')->nullable();
            $table->unsignedBigInteger('billing_country_id')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone_number')->nullable();
            $table->text('billing_customer_notes')->nullable();


        });

        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
