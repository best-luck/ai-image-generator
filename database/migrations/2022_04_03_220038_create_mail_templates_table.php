<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lang', 3);
            $table->string('alias');
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->longText('shortcodes')->nullable();
            $table->boolean('status')->default(true);
            $table->foreign("lang")->references("code")->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_templates');
    }
}
