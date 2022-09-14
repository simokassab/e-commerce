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
        Schema::table('settings', function (Blueprint $table) {
            DB::statement("ALTER TABLE settings CHANGE COLUMN type type ENUM('number', 'text', 'checkbox', 'select', 'multi-select','model_select') NOT NULL ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            DB::statement("ALTER TABLE settings CHANGE COLUMN type ENUM('number', 'text', 'checkbox', 'select', 'multi-select') NOT NULL ");
        });
    }
};
