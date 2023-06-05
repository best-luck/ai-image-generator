<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooterMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->text('link');
            $table->string('lang', 3);
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('order')->default(0);
            $table->foreign("lang")->references("code")->on('languages')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('footer_menu')->onDelete('cascade');
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
        Schema::dropIfExists('footer_menu');
    }
}