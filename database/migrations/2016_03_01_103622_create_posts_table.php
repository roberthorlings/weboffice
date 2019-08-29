<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('posts', function(Blueprint $table) {
                $table->increments('id');
                $table->string('nummer');
				$table->string('omschrijving');
				$table->double('percentage_aftrekbaar');
				$table->foreign('post_type_id')->references('id')->on('posttypes');

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
        Schema::drop('posts');
    }

}
