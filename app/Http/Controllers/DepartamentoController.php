<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Departamentos;

class DepartamentoController extends Controller {

    public function inicio() {
        $departamento = new Departamentos();
        return view('departamento.inicio', ['departamentos' => $departamento->getDepartamentos()]);
    }

    public function add() {
        return view('departamento.form');
    }

    public function save(Request $request) {
        $validacion = $this->validarForm($request);
        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion->errors());
        } else {
            $depa = Departamentos::withData(0, $request->nombre, $request->descripcion);
            $response = $depa->insertDepartamento($depa);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje', $response['error']);
            } else {
                return redirect()->action("DepartamentoController@inicio")->with('mensaje', $response['success']);
            }
        }
    }

    public function modificar(Request $request) {
        $departamento = new Departamentos();
        $departamento->getDepartamento($request->id);
        return view('departamento.formMod', ['departamento' => $departamento]);
    }
    public function saveMod(Request $request){
        $validacion = $this->validarForm($request);
        if ($validacion->fails()){
            return redirect()->back()->withErrors($validacion->errors());
        }else{
            $depa = Departamentos::withData($request->idDepa, $request->nombre, $request->descripcion);
            $response = $depa->updateDepartamento($depa);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje',$response['error']);
            } else {
                return redirect()->action("DepartamentoController@inicio")->with('mensaje',$response['success']);
            }
        }
    }
    public function delete(Request $request){
        $departamentos = new Departamentos();
        $response = $departamentos->deleteDepartamento($request->id);
        if (isset($response['error'])){
            return $response['error'];
        }else return $response['success'];
    }
    public function validarForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'descripcion' => 'required',
            'nombre' => 'required',
        ]);
        return $validacion;
    }
}
