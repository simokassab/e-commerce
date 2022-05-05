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
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete()->cascadeOnUpdate();
            $table->string('code');
            $table->string('sku');
            $table->enum('type',['normal,bundle,service,variable,variable_child']);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete()->cascadeOnUpdate();
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->nullable();
            $table->integer('minimum_quantity')->default(0);
            $table->json('summary');
            $table->json('specification');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('tax_id');
            $table->foreign('tax_id')->references('id')->on('taxes')->nullOnDelete()->cascadeOnUpdate();
            $table->json('title');
            $table->json('description');
            $table->json('keyword');
            $table->enum('status',['draft,pending_review,published']);
            $table->string('barcode');
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('length')->nullable();
            $table->double('weight')->nullable();
            $table->boolean('is_disabled')->default(0);
            $table->string('sort')->nullable();
            $table->unsignedBigInteger('parent_product_id')->nullable();
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
