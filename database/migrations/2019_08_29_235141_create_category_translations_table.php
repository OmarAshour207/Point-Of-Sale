<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTranslationsTable extends Migration
{

    public function up()
    {
        Schema::create('category_translations', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['category_id','locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_translate');
    }
}
