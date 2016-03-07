<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('invoices', function(Blueprint $table) {
                $table->increments('id');
                $table->string('factuurnummer');
				$table->integer('versie');
				$table->string('titel');
				$table->string('referentie');
				$table->integer('totaalbedrag');
				$table->date('datum');
				$table->boolean('definitief');
				$table->boolean('uurtje_factuurtje');
				$table->boolean('btw');
				$table->boolean('creditfactuur');
				$table->string('oorspronkelijk_factuurnummer');
				$table->date('oorspronkelijk_datum');
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
        Schema::drop('invoices');
    }

}
