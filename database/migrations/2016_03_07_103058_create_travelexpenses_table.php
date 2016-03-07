<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTravelexpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('travelexpenses', function(Blueprint $table) {
                $table->increments('id');
                $table->string('van_naar');
$table->text('bezoekadres');
$table->integer('km_begin');
$table->integer('km_eind');
$table->integer('afstand');
$table->string('wijze');
$table->foreign('werktijd_id')->references('id')->on('werktijden');

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
        Schema::drop('travelexpenses');
    }

}
