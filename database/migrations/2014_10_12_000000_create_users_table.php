<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('mobile', 50)->unique();
            $table->text('address');
            $table->string('avatar');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('google2fa_status')->default(false)->comment('0: Disabled, 1: Active');;
            $table->text('google2fa_secret')->nullable();
            $table->boolean('status')->default(true)->comment('0: Banned, 1: Active');
            $table->rememberToken();
            $table->boolean('is_viewed')->default(false);
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
        Schema::dropIfExists('users');
    }
}
