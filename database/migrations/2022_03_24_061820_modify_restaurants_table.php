<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->enum('cash_on_delivery',['yes','no'])->default('no');
            $table->enum('takeaway',['yes','no'])->default('no');
            $table->enum('table_booking',['yes','no'])->default('no');
            $table->text('direction')->nullable();
            $table->string('delivery_fee')->nullable();
            $table->enum('on_multi_restaurant',['publish','unpublish'])->default('unpublish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('cash_on_delivery');
            $table->dropColumn('takeaway');
            $table->dropColumn('table_booking');
            $table->dropColumn('direction');
            $table->dropColumn('delivery_fee');
            $table->dropColumn('on_multi_restaurant');
        });
    }
}
