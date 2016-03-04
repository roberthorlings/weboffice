<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('accounts', function(Blueprint $table) {
                $table->increments('id');
                $table->string('rekeningnummer');
				$table->string('omschrijving');
				$table->string('bank');
				$table->foreign('post_id')->references('id')->on('posten');
				$table->date('saldodatum');
				$table->integer('saldo');

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
        Schema::drop('accounts');
    }

}
