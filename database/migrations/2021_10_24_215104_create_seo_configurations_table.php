<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lang', 3)->unique();
            $table->string('title', 70);
            $table->string('description', 150);
            $table->string('keywords', 255);
            $table->string('robots_index', 50);
            $table->string('robots_follow_links', 50);
            $table->foreign("lang")->references("code")->on('languages')->onDelete('cascade');
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
        Schema::dropIfExists('seo_configurations');
    }
}
