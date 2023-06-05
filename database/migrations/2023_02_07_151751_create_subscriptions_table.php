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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('plan_id')->unsigned();
            $table->bigInteger('generated_images')->default(0)->unsigned();
            $table->boolean('status')->default(true)->comment('1:Active 0:cancelled');
            $table->dateTime('expiry_at');
            $table->boolean('about_to_expire_reminder')->default(false);
            $table->boolean('expired_reminder')->default(false);
            $table->boolean('is_viewed')->default(false);
            $table->foreign("user_id")->references("id")->on('users')->onDelete('cascade');
            $table->foreign("plan_id")->references("id")->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('subscriptions');
    }
};