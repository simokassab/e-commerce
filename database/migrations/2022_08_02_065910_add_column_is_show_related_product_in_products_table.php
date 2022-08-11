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
        if(!Schema::hasColumn('products','is_show_related_product')){
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_show_related_product')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if(Schema::hasColumn('products','is_show_related_product')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_show_related_product');
            });
        }


    }
};
