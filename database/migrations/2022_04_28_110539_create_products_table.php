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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('code');
            $table->string('sku');
            $table->enum('type',['normal,bundle,service,variable,variable_child']);
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->integer('quantity');
            $table->integer('reserved_quantity');
            $table->integer('minimum_quantity');
            $table->json('summary');
            $table->json('specification');
            $table->string('image');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('tax_id');
            $table->foreign('tax_id')->references('id')->on('taxes');
            $table->json('title');
            $table->json('description');
            $table->json('keyword');
            $table->enum('status',['draft,pending_review,published']);
            $table->string('barcode');
            $table->double('height');
            $table->double('width');
            $table->double('length');
            $table->double('weight');
            $table->boolean('is_disabled')->default(0);
            $table->string('sort');
            $table->unsignedBigInteger('parent_product_id');
            $table->boolean('is_default_child')->default(0);
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
        Schema::dropIfExists('products');
    }
};
