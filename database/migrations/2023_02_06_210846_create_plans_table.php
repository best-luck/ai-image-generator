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
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('short_description', 150);
            $table->tinyInteger('interval')->comment('1:Monthly 2:Yearly');
            $table->float('price', 10, 2)->default(0);
            $table->bigInteger('images')->unsigned();
            $table->integer('max_images')->unsigned()->comment('Form 1 to 10');
            $table->text('sizes');
            $table->integer('expiration')->nullable();
            $table->boolean('advertisements')->default(false);
            $table->longText('custom_features')->nullable();
            $table->boolean('is_free')->default(false)->comment('0:No 1:Yes');
            $table->boolean('login_require')->default(true)->comment('0:No 1:Yes');
            $table->boolean('is_featured')->default(false)->comment('0:No 1:Yes');
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
        Schema::dropIfExists('plans');
    }
};