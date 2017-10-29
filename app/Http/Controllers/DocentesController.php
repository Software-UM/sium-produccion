<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
class DocentesController extends Controller
{
    //
	public function dashDocente(){
		$personal = new Empleados();
		$personal->getSingleEmpleado(Auth::user()->empleado_id);
		return view("dashboarddocente",['empleado'=>$personal]);
	}
}
