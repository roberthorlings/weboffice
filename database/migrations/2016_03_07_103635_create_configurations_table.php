<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('configurations', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name');
$table->text('value');
$table->string('title');
$table->string('type');
$table->string('categorie');
$table->integer('categorie_volgorde');

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
        Schema::drop('configurations');
    }

}
