<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Empleado;
use App\Plantel;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    public function inicio(){
    	$empleado = Empleado::find(Auth::user()->empleado_id);
        $plantel = Plantel::find($empleado->cct_plantel);
        return view('control.inicio', compact('plantel'));
    }
}
