<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lang', 3);
            $table->bigInteger('admin_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image');
            $table->text('content');
            $table->string('short_description', 200);
            $table->bigInteger('views')->default(0);
            $table->foreign("category_id")->references("id")->on('blog_categories')->onDelete('cascade');
            $table->foreign("admin_id")->references("id")->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('blog_articles');
    }
}
