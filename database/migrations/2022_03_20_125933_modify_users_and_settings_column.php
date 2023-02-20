<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersAndSettingsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status',['pending','approved','banned'])->default('pending');
            $table->string('restaurant_owner_id')->nullable();
        });
        DB::statement('ALTER TABLE settings CHANGE  value value LONGTEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('restaurant_owner_id');
        });
        DB::statement('ALTER TABLE settings CHANGE  value value TEXT');
    }
}
