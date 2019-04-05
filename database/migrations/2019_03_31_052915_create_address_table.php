<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8_general_ci';
            $table->charset   = 'utf8';

            $table->increments('id');
            $table->string    ('street'   , 255);
            $table->char      ('zip'      , 6);
            $table->string    ('city_name', 255);
            $table->decimal   ('longitude', 10,6)->nullable();
            $table->decimal   ('latitude' , 10,8)->nullable();
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
        Schema::dropIfExists('address');
    }
}
