<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias');
            $table->string('handler', 255);
            $table->string('logo');
            $table->longText('credentials');
            $table->text('instructions')->nullable();
            $table->boolean('status')->default(false)->comment('0:Disabled 1:Enabled');
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
        Schema::dropIfExists('storage_providers');
    }
}