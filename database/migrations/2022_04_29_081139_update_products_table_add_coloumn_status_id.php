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
        Schema::table('products',function (Blueprint $table){
            $table->unsignedBigInteger('products_statuses_id');
            $table->foreign('products_statuses_id','products_statuses_id_products_statuses')->references('id')->on('products_statuses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('products' , 'products_statuses_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign('products_statuses_id_products_statuses');
                $table->dropColumn('products_statuses_id');
            });
        }
    }
};
