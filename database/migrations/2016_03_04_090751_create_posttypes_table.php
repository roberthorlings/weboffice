<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePosttypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('posttypes', function(Blueprint $table) {
                $table->increments('id');
                $table->string('type');
				$table->string('omschrijving');
				$table->string('balanszijde');
				$table->boolean('draagt_bij_aan_resultaat');

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
        Schema::drop('posttypes');
    }

}
