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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('code');
            $table->string('image')->nullable();
            $table->string('icon')->default('default');
            $table->unsignedBigInteger('parent_id')->nullable();//foregin key in another migration
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('description');
            $table->json('keyword');
            $table->integer('sort')->nullable();
            $table->boolean('is_disabled')->default(0);
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
        Schema::dropIfExists('categories');
    }
};
