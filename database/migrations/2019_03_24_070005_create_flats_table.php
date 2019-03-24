<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flats', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->timestamps();
	    $table->string('city', 256);
	    $table->string('district', 256);
	    $table->string('address', 256);
	    $table->string('residence', 256);
	    $table->integer('block');
	    $table->integer('floors');
	    $table->integer('floor');
	    $table->integer('rooms');
	    $table->integer('square');
	    $table->integer('rant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flats');
    }
}