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
        Schema::table('orders',function (Blueprint $table){
           if(!Schema::hasColumn('orders','currency_id')){
               $table->unsignedBigInteger('currency_id')->after('customer_id');
//                $table->foreign('currency_id')->references('id')->on('currencies');

           }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders',function (Blueprint $table){
            if(Schema::hasColumn('orders','currency_id')){
                $table->dropColumn('currency_id');
            }
        });
    }
};
