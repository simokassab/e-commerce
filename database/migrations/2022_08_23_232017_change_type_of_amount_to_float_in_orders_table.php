<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::statement('ALTER TABLE `order_products` MODIFY `quantity` DOUBLE(19,4) NULL');

        DB::statement('ALTER TABLE `products` MODIFY `quantity` DOUBLE(19,4) NULL');
        DB::statement('ALTER TABLE `products` MODIFY `reserved_quantity` DOUBLE(19,4) NULL');
        DB::statement('ALTER TABLE `products` MODIFY `minimum_quantity` DOUBLE(19,4) NULL');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('quantity')->change();

        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->integer('reserved_quantity')->change();
            $table->integer('minimum_quantity')->change();

        });
    }
};
