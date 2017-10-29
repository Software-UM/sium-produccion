<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersDocentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('users_docentes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('usuario')->unique();
			$table->string('password', 100);
			$table->rememberToken();
			$table->timestamps();
			$table->integer('empleado_id')->unsigned();
			$table->integer('tipo')->unsigned()->default(2);
			$table->foreign('empleado_id')->references('id')->on('empleado');

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
