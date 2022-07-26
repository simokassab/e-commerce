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
            $table->json('summary')->nullable()->change();
            $table->json('specification')->nullable()->change();
            $table->string('barcode',250)->nullable()->change();
            $table->string('sku',250)->nullable()->change();
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
            $table->json('summary')->change();
            $table->json('specification')->change();
            $table->string('barcode',250)->change();
            $table->unsignedBigInteger('tax_id')->change();
            $table->string('sku',250)->change();

        });
    }
};
