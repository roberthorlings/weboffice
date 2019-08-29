<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('projects', function(Blueprint $table) {
                $table->increments('id');
                $table->string('naam');
$table->text('opmerkingen');
$table->integer('status');
$table->integer('uurtarief');
$table->foreign('relatie_id')->references('id')->on('relations');
$table->foreign('post_id')->references('id')->on('posts');

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
        Schema::drop('projects');
    }

}
