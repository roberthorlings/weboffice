<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkinghoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('workinghours', function(Blueprint $table) {
                $table->increments('id');
                $table->date('datum');
				$table->time('begintijd');
				$table->time('eindtijd');
				$table->text('opmerkingen');
				$table->integer('kilometers');
				$table->integer('pauze');

				$table->foreign('relatie_id')->references('id')->on('relaties');
				$table->integer('project_id')->references('id')->on('projecten');
				
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
        Schema::drop('workinghours');
    }

}
