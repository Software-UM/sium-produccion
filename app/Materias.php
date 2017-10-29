<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    //
    protected $table = "materias";
    protected $primaryKey = 'clave';
    //se asigna este valor en el caso que la llave primaria sea string
    public $incrementing = false;
    public $timestamps = false;
}
