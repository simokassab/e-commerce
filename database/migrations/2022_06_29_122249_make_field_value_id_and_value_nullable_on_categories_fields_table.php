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
        Schema::table('categories_fields', function (Blueprint $table) {

            if(false){
                $table->unsignedBigInteger('field_value_id')->nullable()->change();
            }

            $table->string('value')->nullable()->change();
            $table->foreign('field_value_id')->references('id')->on('fields_values');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_fields', function (Blueprint $table) {
            $table->dropForeign(['field_value_id']);
            $table->dropColumn('field_value_id');
            $table->dropColumn('value');

        });
    }
};
