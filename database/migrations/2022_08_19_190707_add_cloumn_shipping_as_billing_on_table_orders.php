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
            if(!Schema::hasColumn('orders','is_billing_as_shipping')){
                $table->boolean('is_billing_as_shipping')->after('updated_at')->default(0);
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
            if(Schema::hasColumn('orders','is_billing_as_shipping')){
                $table->dropColumn('is_billing_as_shipping');
            }
        });
    }
};
