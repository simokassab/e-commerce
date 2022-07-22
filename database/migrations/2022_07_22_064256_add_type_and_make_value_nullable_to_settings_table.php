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
        Schema::table('settings', function (Blueprint $table) {
            $table->enum('type', ['number', 'text','checkbox','select','multi-select'])->after('title')	;
            $table->string('value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('type', 'value')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('type');
                $table->string('value')->change();

            });
        }
    }

};
