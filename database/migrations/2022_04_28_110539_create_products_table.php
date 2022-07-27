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
            $table->string('slug',250)->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate();
            $table->string('code',250)->unique();
            $table->string('sku',250)->nullable();
            $table->enum('type',['normal','bundle','service','variable','variable_child']);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnUpdate();
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->nullable();
            $table->integer('minimum_quantity')->default(0);
            $table->json('summary')->nullable();
            $table->json('specification')->nullable();
            $table->text('image',250)->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnUpdate();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keyword')->nullable();
            $table->json('description')->nullable();
            $table->enum('status',['draft','pending_review','published'])->default('draft');
            $table->string('barcode',250)->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('length')->nullable();
            $table->double('weight')->nullable();
            $table->boolean('is_disabled')->default(0);
            $table->integer('sort')->nullable();
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
