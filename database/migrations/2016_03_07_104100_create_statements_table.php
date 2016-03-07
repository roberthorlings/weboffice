<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('statements', function(Blueprint $table) {
                $table->increments('id');
                $table->date('datum');
$table->string('omschrijving');
$table->text('opmerkingen');
$table->boolean('actief');
$table->foreign('transactie_id')->references('id')->on('transacties');
$table->foreign('activum_id')->references('id')->on('activa');

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
        Schema::drop('statements');
    }

}
