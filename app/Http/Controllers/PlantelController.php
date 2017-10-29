<?php

namespace App\Http\Controllers;

use App\Plantel;
use App\Empleado;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Planteles;
use App\Clases\Direcciones;
use App\Estados;
use App\Municipios;

class PlantelController extends Controller {
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inicio() {
        $plantels = new Planteles();
        $lista = $plantels->getPlanteles();
        $datosEmp = Empleado::find(\Auth::user()->empleado_id);
        $plantel = Plantel::find($datosEmp->cct_plantel);
        return view('plantelView.inicio', ['planteles' => $lista, 'plantel' => $plantel]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add() {
        $estados = Estados::all();
        return view('plantelView.form', ['estados' => $estados]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function municipios(Request $request) {
        $municipios = Municipios::where('id_estado', $request->id)->get();
        return $municipios;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'nombre' => 'required',
            'clave' => 'required',
            'municipio' => 'required',
            'calle' => 'required',
            'colonia' => 'required',
            'cp' => 'required|numeric',
        ]);
        //Vaciamos los datos en un arreglo
        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion->errors());
        } else {
            //procedemos a la insercion de datos
            $direccion = Direcciones::withData(0,
                $request->calle, $request->colonia, $request->cp,
                $request->municipio, $request->estados);
            $response = $direccion->insertDireccion($direccion);
            if (isset($response['error'])) {
                return $response['error'];
            } else {
                //return $response['success'].$response['id'];
                $plantel = Planteles::withData(0, $request->nombre, $response['id'], $request->clave);
                $responsePlantel = $plantel->insertPlantel($plantel);
                if (isset($responsePlantel['error'])) {
                    return $responsePlantel['error'];
                } else {
                    return redirect(url("/modules/personal/plantel"))->with('mensaje',$responsePlantel['success']);
                }
            }

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modificar(Request $request) {
        //buscamos los valores del plantel
        $plantel = new Planteles();
        $plantel->getPlantel($request->idPlantel);
        $estados = Estados::all();
        $direccion = new Direcciones();
        $direccion->getDireccion($plantel->getIdDireccion());
        $municipios = Municipios::where('id_estado', $direccion->getEstado())->get();
        $params = ['estados' => $estados, 'plantel' => $plantel,
            'municipio' => $municipios, 'direccion' => $direccion];
        return view('plantelView.formMod', $params);
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function saveMod(Request $request) {
        $validacion = $this->validarForm($request);
        //Vaciamos los datos en un arreglo
        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion->errors());
        } else {
            //procedemos a la insercion de datos
            $direccion = Direcciones::withData($request->idDireccion,
                $request->calle, $request->colonia, $request->cp,
                $request->municipio, $request->estados);
            $response = $direccion->updateDireccion($direccion);
            if (isset($response['error'])) {
                return redirect()->back()->with('mensaje',$response['error']);
            } else {
                //return $response['success'].$response['id'];
                $plantel = Planteles::withData($request->idPlantel, $request->nombre, $direccion->getId(), $request->clave);
                $responsePlantel = $plantel->updatePlantel($plantel);
                if (isset($responsePlantel['error'])) {
                    return  redirect()->back()->with('mensaje',$responsePlantel['error']);
                } else {
                    //aqui mandamos a llamar a nuestro controlador enviandole un nuevo parametro en la session
                    return redirect()->action("PlantelController@inicio")->with('mensaje',$responsePlantel['success']);
                }
            }

        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function validarForm(Request $request) {
        $validacion = \Validator::make($request->all(), [
            'nombre' => 'required',
            'clave' => 'required',
            'municipio' => 'required',
            'calle' => 'required',
            'colonia' => 'required',
            'cp' => 'required|numeric',
        ]);
        return $validacion;
    }
}
