<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserDocente extends Authenticatable
{
	protected $table = "users_docentes";
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'usuario', 'tipo', 'password','empleado_id',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
}
