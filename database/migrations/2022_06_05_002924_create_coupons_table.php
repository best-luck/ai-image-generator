<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->unique();
            $table->bigInteger('percentage')->default(1);
            $table->bigInteger('plan_id')->nullable()->unsigned();
            $table->tinyInteger('action_type');
            $table->bigInteger('limit')->default(1);
            $table->timestamp('expiry_at')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}