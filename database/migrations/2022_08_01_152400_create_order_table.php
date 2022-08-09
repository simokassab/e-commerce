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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('prefix')->unique();
            $table->time('time')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->double('currency_rate')->nullable();
            $table->double('total')->nullable();
            $table->double('tax_total')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->integer('discount_amount')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->text('customer_comment')->nullable();
            $table->unsignedBigInteger('order_status_id')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
