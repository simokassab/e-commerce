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
        Schema::table('prices', function (Blueprint $table) {
            if(Schema::hasColumn('prices', 'original_price')){
                $table->dropColumn('original_price');
            }
            $table->double('percentage')->nullable()->change();
            $table->unsignedBigInteger('original_price_id')->nullable()->after('is_virtual');
            $table->foreign('original_price_id')->references('id')->on('prices');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            if(Schema::hasColumn('prices', 'original_price_id')){
                $table->dropColumn('original_price_id');
            }

            $table->double('original_price')->default(0)->after('is_virtual');
            $table->double('percentage')->change();

        });
    }
};
