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
        Schema::table('products_fields', function (Blueprint $table){
            $table->boolean('is_used_for_variations')->default(0)->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_fields', function (Blueprint $table){
            if (Schema::hasColumn('products_fields','is_used_for_variations')){
                $table->dropColumn('is_used_for_variations');
            }
        });
    }
};
