<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 3);
            $table->string('name', 100);
            $table->string('capital', 100);
            $table->string('continent', 100);
            $table->string('continent_code', 10);
            $table->string('phone', 10);
            $table->string('currency', 10);
            $table->string('symbol', 5);
            $table->string('alpha_3', 10);
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
        Schema::dropIfExists('countries');
    }
}
