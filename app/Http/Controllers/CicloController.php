<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Ciclo;


class CicloController extends Controller {
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function inicio() {
        $ciclo = new Ciclo();
        return view('control.ciclo.inicio', ['ciclos' => $ciclo->getCiclos()]);
    }

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function add() {
        return view('control.ciclo.form');
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
            $ciclos = Ciclo::withData(0, $request->descripcion, $request->nombre, $request->nombrec, 0);
            $response = $ciclos->insertCiclo($ciclos);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje', $response['error']);
            } else {
                return redirect()->action("CicloController@inicio")->with('mensaje', $response['success']);
            }
        }
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function modificar(Request $request) {
        $ciclos = new Ciclo();
        $ciclos->getCiclo($request->id);
        return view('control.ciclo.formMod', ['ciclo' => $ciclos]);
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
            $ciclos = Ciclo::withData($request->idCiclo,
                $request->descripcion, $request->nombre, $request->nombrec, 0);
            $response = $ciclos->updateCiclo($ciclos);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje', $response['error']);
            } else {
                return redirect()->action("CicloController@inicio")->with('mensaje', $response['success']);
            }
        }
    }

	/**
	 * @param Request $request
	 * @return mixed
	 */
    public function setActivo(Request $request){
        $ciclo = new Ciclo();
        $response = $ciclo->setCicloActivo($request->id);
        if (isset($response['error'])) {
            return $response['error'];
        } else return $response['success'];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request) {
        $ciclo = new Ciclo();
        $response = $ciclo->deleteCiclo($request->id);
        if (isset($response['error'])) {
            return $response['error'];
        } else return $response['success'];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function validateForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'descripcion' => 'required',
            'nombre' => 'required',
            'nombrec' => 'required',
        ]);
        return $validacion;
    }
}
