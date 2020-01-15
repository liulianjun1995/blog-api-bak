<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValuesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30);
            $table->string('nickname', 30);
            $table->string('app_id',30);
            $table->string('avatar', 255);
            $table->integer('status')->default(0);
            $table->string('source', 20)->nullable();
            $table->string('last_login_ip', 20)->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
