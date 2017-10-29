<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Materia;

class MateriaController extends Controller
{
    public function inicio(){
        $materias = new Materia();
        return view('control.materia.inicio',['materias'=>$materias->getMaterias()]);
    }

    public function add(){
        return view('control.materia.form');
    }

    public function save(Request $request){
        $validacion = $this->validarForm($request);
        if ($validacion->fails()){
            return redirect()->back()->withErrors($validacion->errors());
        }else{
            $materia = Materia::withData($request->clave,$request->nombre);
            $response = $materia->insertMateria($materia);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje',$response['error']);
            } else {
                return redirect()->action("MateriaController@inicio")->with('mensaje',$response['success']);
            }
        }
    }

    public function modificar(Request $request){
        $materia = new Materia();
        $materia->getMateria($request->id);
        return view('control.materia.formMod',['materia'=>$materia]);
    }

    public function saveMod(Request $request){
        $validacion = $this->validarFormM($request);
        if ($validacion->fails()){
            return redirect()->back()->withErrors($validacion->errors());
        }else{
            $materia = Materia::withData($request->clave,$request->nombre);
            $response = $materia->updateMateria($materia,$request->claveold);
            if (isset($response['error'])) {
                return  redirect()->back()->with('mensaje',$response['error']);
            } else {
                //aqui mandamos a llamar a nuestro controlador enviandole un nuevo parametro en la session
                return redirect()->action("MateriaController@inicio")->with('mensaje',$response['success']);
            }
        }
    }

    public function delete(Request $request){
        $materia = new Materia();
        $response = $materia->deleteMateria($request->id);
        if (isset($response['error'])){
            return $response['error'];
        }else return $response['success'];
    }
    public function validarForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'nombre' => 'required',
            'clave' => 'required|unique:materias',
        ]);
        return $validacion;
    }
    public function validarFormM(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'nombre' => 'required',
        ]);
        return $validacion;
    }
}
