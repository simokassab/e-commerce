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
        Schema::create('client_courier_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_company_id');
            $table->boolean('is_default')->nullable()->default(false);
            $table->unsignedBigInteger('priority')->default(0)->nullable();
            $table->unsignedBigInteger('capacity')->default(0)->nullable();
            $table->float('weight_limit')->default(0)->nullable();
            $table->jsonb('cities')->nullable();
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
        Schema::dropIfExists('client_courier_companies');
    }
};
