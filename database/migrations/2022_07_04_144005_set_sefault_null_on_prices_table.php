<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('prices',function (Blueprint $table){
            if(Schema::hasColumn('prices','original_percent')){
                $table->dropColumn('original_percent');
            }
            DB::statement('ALTER TABLE `prices` MODIFY `percentage` DOUBLE(19,4) NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices',function (Blueprint $table) {
           $table->double('original_percent')->nullable()->default(null);
        });
        }
};
