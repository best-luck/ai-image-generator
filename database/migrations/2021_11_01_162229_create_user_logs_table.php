<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('ip', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('country_code', 100)->nullable();
            $table->string('timezone', 150)->nullable();
            $table->string('location', 60)->nullable();
            $table->string('latitude', 60)->nullable();
            $table->string('longitude', 60)->nullable();
            $table->string('browser', 60)->nullable();
            $table->string('os', 60)->nullable();
            $table->foreign("user_id")->references("id")->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
