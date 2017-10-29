<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Carreras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 255);
            $table->string('rvoe', 255);
            $table->integer('cct_plantel')->unsigned();
            $table->integer('id_modalidad')->unsigned();
            $table->foreign('cct_plantel')->references('cct')->on('plantel');
            $table->foreign('id_modalidad')->references('id')->on('modalidad');
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
        Schema::drop('carreras');
    }
}
