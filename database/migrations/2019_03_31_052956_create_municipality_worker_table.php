<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipalityWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipality_worker', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8_general_ci';
            $table->charset   = 'utf8';

            $table->increments('id');
            $table->integer   ('municipality_id')->unsigned();
            $table->string    ('name'    , 255);
            $table->string    ('position', 255);
            $table->timestamps();

            $table->foreign('municipality_id')->references('id')->on('municipality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipality_worker');
    }
}
