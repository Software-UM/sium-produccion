<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\TipoEmpleados;

class TipoEmpleadoController extends Controller
{
    //
    public function inicio(){
        $tipo = new TipoEmpleados();
        $tipos = $tipo->getTipos();
        return view("PersonalViews.tipoEmpleado.inicio",['tipos'=>$tipos]);
    }

    public function add(){
        return view("PersonalViews.tipoEmpleado.form");
    }

    public function save(Request $request){
        $validacion = $this->validarForm($request);
        if ($validacion->fails()){
            return redirect()->back()->withErrors($validacion->errors());
        }else{
            $tipo = TipoEmpleados::withData(0,$request->descripcion);
            $response = $tipo->insertTipoEmpleado($tipo);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje',$response['error']);
            } else {
                return redirect()->action("TipoEmpleadoController@inicio")->with('mensaje',$response['success']);
            }
        }
    }

    public function saveMod(Request $request){
        $validacion = $this->validarForm($request);
        if ($validacion->fails()){
            return redirect()->back()->withErrors($validacion->errors());
        }else{
            $tipo = TipoEmpleados::withData($request->idTipo,$request->descripcion);
            $response = $tipo->updateTipoEmpleado($tipo);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje',$response['error']);
            } else {
                return redirect()->action("TipoEmpleadoController@inicio")->with('mensaje',$response['success']);
            }
        }
    }

    public function modificar(Request $request){
        $tipoEmpleado = new TipoEmpleados();
        $tipoEmpleado->getTipoEmpleado($request->id);
        return view('PersonalViews.tipoEmpleado.formMod',['tipo' => $tipoEmpleado]);
    }

    public function validarForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'descripcion' => 'required'
        ]);
        return $validacion;
    }

    public function delete(Request $request){
        $tipoEmpleado = TipoEmpleados::withData($request->id,$request->descripcion);
        $response = $tipoEmpleado->deleteTipoEmpleado($tipoEmpleado->getId());
        if (isset($response['error'])){
            return $response['error'];
        }else return $response['success'];
    }
}
