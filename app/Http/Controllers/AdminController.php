<?php

namespace App\Http\Controllers;

use App\User;
use App\Clases\Empleados;
use App\TipoBaja;
use App\Plantel;
use App\Empleado;

class AdminController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('super');
	}

	public function index() {
		$datosEmp = Empleado::find(\Auth::user()->empleado_id);
		$plantel = Plantel::find($datosEmp->cct_plantel);
		return view('admin/index', compact('plantel'));
	}

	public function personal() {
		$datosEmp = Empleado::find(\Auth::user()->empleado_id);
		$empleados = new Empleados();
		$tipoBajas = TipoBaja::all();
		return view('admin/personal', ['empleados' => $empleados->getEmpleados($datosEmp->cct_plantel), 'tipos' => $tipoBajas]);
	}
}
