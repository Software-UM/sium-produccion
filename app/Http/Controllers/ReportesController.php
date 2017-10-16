<?php

namespace App\Http\Controllers;

use App\Clases\Ciclo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\reportes\ReporteDocente;
use App\Clases\Empleados;
use App\Clases\reportes\ReporteAsignaciones;
use App\Clases\Horarios;
use App\Plantel;

class ReportesController extends Controller
{

	/**
	 * Funcion menu principal para el reporteador
	 * PENDIENTE: Aun falta el recibir el numero de plantel para este reporte
	 * 				Por el momento se esta haciendo de forma manual
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function menu(){
		//obtenemos los ciclos
		$ciclos = new Ciclo();
        $ciclo = $ciclos->getCiclos();
        $idEmpleado = \Auth::user()->empleado_id;
		//cargamos los empleados generales por plantlel
		$empleados = new Empleados();
        $singleEmpleado = $empleados->getSingleEmpleado($idEmpleado);
        $plantel = Plantel::find($singleEmpleado->cct_plantel);
		//retornamos la vista y el empleado por plantel
		return view('reportes.inicio',['empleados'=>$empleados->getEmpleadosPlantel($singleEmpleado->cct_plantel),
            'docentes'=>$empleados->getDocentes($singleEmpleado->cct_plantel),"ciclos"=>$ciclo, 
			"cct_plantel"=>$singleEmpleado->cct_plantel, 'plantel' => $plantel]);
	}
	public function general(Request $request){
		ReporteDocente::imprimeGeneral($request->modalidad, $request->plantel);
	}

	public function generar(Request $request){
		//$request->fechaInicio;
		//$request->fechaFin;
        $idEmpleado = \Auth::user()->empleado_id;
        //cargamos los empleados generales por plantlel
        $empleados = new Empleados();
        $singleEmpleado = $empleados->getSingleEmpleado($idEmpleado);
		switch ($request->act){
			case 1:
			    //obtenemos el ciclo activo
                $ciclos = new Ciclo();
                $ciclo = $ciclos->getCicloActivo();
				//Llamamos el reporte del docente
                ReporteDocente::imprimeDocente($request->fechaInicio,$request->fechaFin,$ciclo[0]['id'],$singleEmpleado->cct_plantel);
				break;
			case 2:
				ReporteDocente::imprimeAdmon($request->fechaInicio,$request->fechaFin,$singleEmpleado->cct_plantel);
				break;
			case 3:
				ReporteDocente::imprimeDesayunos($request->fechaInicio,$request->fechaFin,$singleEmpleado->cct_plantel);
				break;
			case 4:
				//obtenemos el ciclo activo
				$ciclos = new Ciclo();
				$ciclo = $ciclos->getCicloActivo();
				//Llamamos el reporte del docente
				ReporteDocente::imprimeConcentrado($request->fechaInicio,$request->fechaFin,$ciclo[0]['id'],$singleEmpleado->cct_plantel);
				break;
		}
	}

	public function generarIndividual(Request $request){
			ReporteDocente::generaIndividual($request->empleado,$request->fechaInicio1,$request->fechaFin1);
	}

	/**
	 * metodo para generar la carga academica
	 * manda a llamar otro petodo pasandole un id del docente
	 * @param Request $request
	 */
	public function generarCarga(Request $request){
		//Obtenemos el id del empleado
		$idEmpleado = $request->empleadoC;
		$idCiclo = $request->ciclo;
		ReporteAsignaciones::generarCarga2($idEmpleado, $idCiclo);
	}

	public function validateCarga(Request $request){
		$idEmpleado = $request->idEmpleado;
		$idCiclo = $request->idCiclo;
		$horarios = Horarios::getCargaAcademica($idEmpleado,$idCiclo);
		if (count($horarios)>0)
			return 1;
		else
			return 2;
	}
}
