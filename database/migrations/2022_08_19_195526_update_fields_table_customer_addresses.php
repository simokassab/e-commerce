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
        Schema::table('customer_address',function (Blueprint $table){

            if(Schema::hasColumn('customer_address','detail_address')){
                $table->dropColumn('detail_address');
            }

            $table->string('first_name')->nullable()->after('customer_id');
            $table->string('last_name')->nullable()->after('customer_id');
            $table->string('company_name')->nullable()->after('customer_id');
            $table->text('address_1')->nullable()->after('customer_id');
            $table->text('address_2')->nullable()->after('customer_id');
            $table->string('email_address')->nullable()->after('customer_id');
            $table->string('phone_number')->nullable()->after('customer_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_address',function (Blueprint $table){

            if(!Schema::hasColumn('customer_address','detail_address')){
                $table->text('detail_address')->nullable();
            }

            if(Schema::hasColumn('customer_address','first_name')){
                $table->dropColumn('first_name');
            }

            if(Schema::hasColumn('customer_address','last_name')){
                $table->dropColumn('last_name');
            }

            if(Schema::hasColumn('customer_address','company_name')){
                $table->dropColumn('company_name');
            }

            if(Schema::hasColumn('customer_address','address_1')){
                $table->dropColumn('address_1');
            }

            if(Schema::hasColumn('customer_address','address_2')){
                $table->dropColumn('address_2');
            }

            if(Schema::hasColumn('customer_address','email_address')){
                $table->dropColumn('email_address');
            }

            if(Schema::hasColumn('customer_address','phone_number')){
                $table->dropColumn('phone_number');
            }

        });
    }
};
