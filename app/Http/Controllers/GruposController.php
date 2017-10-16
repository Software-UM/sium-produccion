<?php

namespace App\Http\Controllers;


use App\Clases\Mod;
use Illuminate\Http\Request;
use App\Clases\Grupo;
use App\Http\Requests;

class GruposController extends Controller {
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function inicio() {
		$grupos = new Grupo();
		return view('control.grupo.inicio', ['grupos' => $grupos->getGrupos()]);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function add() {
		$modalidad = new Mod();
		return view('control.grupo.form', ['modalidad' => $modalidad->getListModalidad()]);
	}

	/**
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function save(Request $request) {
		$validacion = $this->validarForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withErrors($validacion->errors());
		} else {
			$grupo = Grupo::withData(0, $request->grupo, $request->grado, $request->modalidad);
			$response = $grupo->insertGrupo($grupo);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else {
				return redirect()->action("GruposController@inicio")->with('mensaje', $response['success']);
			}
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function modificar(Request $request) {
		$grupos = new Grupo();
		$grupos->getGrupoM($request->id);
		$modalidad = new Mod();
		return view('control.grupo.formMod', ['grupo' => $grupos,
			'modalidad' => $modalidad->getListModalidad()]);
	}

	/**
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function saveMod(Request $request) {
		$validacion = $this->validarForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withErrors($validacion->errors());
		} else {
			$grupos = Grupo::withData($request->idGrupo, $request->grupo, $request->grado, $request->modalidad);
			$response = $grupos->updateGrupo($grupos);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else {
				return redirect()->action("GruposController@inicio")->with('mensaje', $response['success']);
			}
		}
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function delete(Request $request){
		$grupos = new Grupo();
		$response = $grupos->deleteGrupo($request->id);
		if (isset($response['error'])) {
			return $response['error'];
		} else return $response['success'];
	}

	public function getGrupos(Request $request){
		$grupos = new Grupo();
		return $grupos->getGruposMod($request->idmodalida);
	}
	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function validarForm(Request $request) {
		$validacion = \Validator::make($request->all(), [
			'grado' => 'required',
			'grupo' => 'required',
		]);
		return $validacion;
	}
}
