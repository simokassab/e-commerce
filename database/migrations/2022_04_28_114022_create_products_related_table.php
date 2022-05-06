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
        Schema::create('products_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_product_id');
            $table->unsignedBigInteger('child_product_id');
            $table->foreign('parent_product_id')->references('id')->on('products');
            $table->foreign('child_product_id')->references('id')->on('products');

            $table->double('child_quantity')->default(1);
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
        Schema::dropIfExists('products_related');
    }
};
