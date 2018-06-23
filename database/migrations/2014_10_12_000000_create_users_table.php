<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('spotify_id');
            $table->string('name');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('access_token', 500);
            $table->string('refresh_token');
            $table->string('uri');
            $table->string('profile_image')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
