<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('quotes', function(Blueprint $table) {
                $table->increments('id');
                $table->string('offertenummer');
$table->integer('versie');
$table->string('titel');
$table->integer('totaalbedrag');
$table->date('datum');
$table->date('vervaldatum');
$table->boolean('definitief');
$table->foreign('relatie_id')->references('id')->on('relaties');
$table->foreign('project_id')->references('id')->on('projects');

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
        Schema::drop('quotes');
    }

}
