<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Puestos;

class PuestoController extends Controller {
    public function inicio() {
        $puestos = new Puestos();
        return view("PersonalViews.puesto.inicio", ['puestos' => $puestos->getPuestos()]);
    }

    public function add() {
        return view('PersonalViews.puesto.form');
    }

    public function save(Request $request) {
        $validacion = $this->validarForm($request);
        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion->errors());
        } else {
            $puesto = Puestos::withData(0, $request->descripcion);
            //$tipo = TipoEmpleados::withData(0, $request->descripcion);
            $response = $puesto->insertPuesto($puesto);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje', $response['error']);
            } else {
                return redirect()->action("PuestoController@inicio")->with('mensaje', $response['success']);
            }
        }
    }

    public function modificar(Request $request) {
        $puesto = new Puestos();
        $puesto->getPuesto($request->id);
        return view('PersonalViews.puesto.formMod', ['puestos' => $puesto]);
    }

    public function saveMod(Request $request) {
        $validacion = $this->validarForm($request);
        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion->errors());
        } else {
            $tipo = Puestos::withData($request->idPuesto, $request->descripcion);
            $response = $tipo->updatePuesto($tipo);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje', $response['error']);
            } else {
                return redirect()->action("PuestoController@inicio")->with('mensaje', $response['success']);
            }
        }
    }

    public function delete(Request $request) {
        $tipoEmpleado = Puestos::withData($request->id, $request->descripcion);
        $response = $tipoEmpleado->delete($tipoEmpleado->getId());
        if (isset($response['error'])) {
            return $response['error'];
        } else return $response['success'];
    }

    public function validarForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'descripcion' => 'required'
        ]);
        return $validacion;
    }
}
