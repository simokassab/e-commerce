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
        Schema::table('customer_address',function (Blueprint $table){
            $table->unsignedBigInteger('payment_method_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('payment_method_id','customer_address')){
            Schema::table('customer_address',function (Blueprint $table){
                $table->dropColumn('payment_method_id');
            });
        }
    }
};
