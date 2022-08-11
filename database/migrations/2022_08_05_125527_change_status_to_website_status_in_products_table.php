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
        Schema::table('products', function (Blueprint $table) {
            if(Schema::hasColumn('products','status')){
            $table->dropColumn('status');
            }
            if(!Schema::hasColumn('products','website_status')){
                $table->enum('website_status',['draft','published','pending_review'])->default('draft');
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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('status',['draft','published','pending_review'])->default('draft');
            $table->dropColumn('website_status');

        });
    }
};
