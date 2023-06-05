<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('alias', 100);
            $table->string('handler', 255);
            $table->string('logo', 255);
            $table->text('supported_currencies')->nullable();
            $table->integer('fees');
            $table->double('min', 10, 2);
            $table->boolean('test_mode')->nullable()->comment('null 0:Disbaled 1:Enabled');
            $table->text('credentials');
            $table->text('instructions')->nullable();
            $table->boolean('status')->default(false)->comment('0:Disabled 1:Active');
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
        Schema::dropIfExists('payment_gateways');
    }
}
