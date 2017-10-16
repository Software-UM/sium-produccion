<?php

namespace App\Http\Controllers;

use App\Clases\Grupo;
use App\Clases\Mod;
use App\Clases\Planteles;
use App\Clases\Ciclo;
use Illuminate\Http\Request;
use App\Clases\Empleados;
use App\Http\Requests;
use App\Clases\Carrera;
use App\Plantel;
use App\Empleado;
use Illuminate\Support\Facades\Auth;

class CarreraController extends Controller {
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function inicio() {
		$carrera = new Carrera();
		return view('control.carrera.inicio', ['carreras' => $carrera->getCarreras()]);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function add() {
		$planteles = new Planteles();
		$modalidad = new Mod();
		return view('control.carrera.form', ['planteles' => $planteles->getPlanteles()
			, 'modalidad' => $modalidad->getListModalidad()]);
	}

	/**
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function save(Request $request) {
		$validacion = $this->validateForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withErrors($validacion->errors());
		} else {
			$carrera = Carrera::withData(0, $request->nombre, $request->rvoe,
				$request->plantel, $request->modalidad);
			$response = $carrera->insertCarrera($carrera);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else
				return redirect()->action("CarreraController@inicio")->with('mensaje', $response['success']);
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function modificar(Request $request) {
		$carreras = new Carrera();
		$carreras->getCarrera($request->id);
		$planteles = new Planteles();
		$modalidad = new Mod();
		return view('control.carrera.formMod', ['carrera' => $carreras,
			'planteles' => $planteles->getPlanteles()
			, 'modalidad' => $modalidad->getListModalidad()]);
	}

	/**
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function saveMod(Request $request) {
		$validacion = $this->validateForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withErrors($validacion->errors());
		} else {
			$carreras = Carrera::withData($request->idC, $request->nombre, $request->rvoe,
				$request->plantel, $request->modalidad);
			$response = $carreras->updateCarrera($carreras);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else
				return redirect()->action("CarreraController@inicio")->with('mensaje', $response['success']);
		}
	}

	public function delete(Request $request) {
		$carrera = new Carrera();
		$response = $carrera->deleteCarrera($request->id);
		if (isset($response['error']))
			return $response['error'];
		else
			return $response['success'];
	}

	/**
	 * Función para obtener los
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function seleccionar() {
		$ciclo = new Ciclo();
		$datosEmp = Empleado::find(Auth::user()->empleado_id);
		$plantel = Plantel::find($datosEmp->cct_plantel);
		return view('PersonalViews.Horario.selectGrupo',['ciclos'=>$ciclo->getCiclos(), 'plantel' => $plantel]);
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function getCarreras(Request $request){
        $idEmpleado = \Auth::user()->empleado_id;
        $empleado = new Empleados();
        $singleEmpleado = $empleado->getSingleEmpleado($idEmpleado);
		$carreras = new Carrera();
		$response = $carreras->getCarrerasMod($request->id,$singleEmpleado->cct_plantel);
		return $response;
		//echo $request->id;
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function validateForm(Request $request) {
		$validacion = \Validator::make($request->all(), [
			'nombre' => 'required',
			'rvoe' => 'required',
		]);
		return $validacion;
	}
}
