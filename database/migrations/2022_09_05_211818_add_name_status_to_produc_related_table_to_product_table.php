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
        Schema::table('products_related', function (Blueprint $table) {
            $table->enum('child_name_status', ['default', 'hide', 'custom'])->default('hide')->after('child_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_related', function (Blueprint $table) {
            if (Schema::hasColumn('products_related', 'child_name_status')) {
                $table->dropColumn('child_name_status');
            }
        });
    }
};
