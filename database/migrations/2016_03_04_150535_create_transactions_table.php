<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('transactions', function(Blueprint $table) {
                $table->increments('id');
                $table->string('omschrijving');
$table->integer('bedrag');
$table->date('datum');
$table->string('tegenrekening');
$table->boolean('ingedeeld');
$table->foreign('rekening_id')->references('id')->on('transacties');

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
        Schema::drop('transactions');
    }

}
