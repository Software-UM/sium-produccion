<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plantel extends Model
{
    //
    protected $table = "plantel";
    protected $primaryKey = 'cct';
    public $timestamps = false;
}
