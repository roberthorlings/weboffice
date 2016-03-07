<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('assets', function(Blueprint $table) {
                $table->increments('id');
                $table->text('omschrijving');
$table->date('aanschafdatum');
$table->date('begin_afschrijving');
$table->bigInteger('bedrag');
$table->integer('restwaarde');
$table->integer('afschrijvingsduur');
$table->integer('afschrijvingsperiode');
$table->foreign('post_investering')->references('id')->on('posten');
$table->foreign('post_afschrijving')->references('id')->on('posten');
$table->foreign('post_kosten')->references('id')->on('posten');

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
        Schema::drop('assets');
    }

}
