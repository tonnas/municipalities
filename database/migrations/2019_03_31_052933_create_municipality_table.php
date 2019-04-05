<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipality', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8_general_ci';
            $table->charset   = 'utf8';

            $table->increments('id');
            $table->integer   ('address_id')->unsigned();
            $table->string    ('url_id', 255);
            $table->string    ('name'  , 255);
            $table->string    ('email' , 255)->nullable();
            $table->string    ('phone' , 255)->nullable();
            $table->string    ('fax'   , 255)->nullable();
            $table->string    ('web'   , 255)->nullable();
            $table->timestamps();

            $table->unique ('url_id');
            $table->foreign('address_id')->references('id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipality');
    }
}
