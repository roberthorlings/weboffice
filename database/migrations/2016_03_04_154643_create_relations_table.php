<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('relations', function(Blueprint $table) {
                $table->increments('id');
                $table->string('bedrijfsnaam');
$table->string('contactpersoon');
$table->string('adres');
$table->string('postcode');
$table->string('plaats');
$table->string('land');
$table->string('email');
$table->string('telefoon');
$table->string('fax');
$table->string('mobiel');
$table->string('website');
$table->text('opmerkingen');
$table->integer('project_count');
$table->string('postadres');
$table->string('postpostcode');
$table->string('postplaats');
$table->string('postland');
$table->integer('type');
$table->boolean('werktijd');
$table->text('factuuradres');

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
        Schema::drop('relations');
    }

}
