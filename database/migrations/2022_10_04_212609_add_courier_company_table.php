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
        Schema::create('courier_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->jsonb('params')->nullable();
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
        Schema::dropIfExists('courier_companies');
    }
};
