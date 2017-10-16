<?php

namespace App\Http\Controllers\Docente;

use App\Clases\Asistencias;
use App\Clases\Empleados;
use App\Clases\Horarios;
use App\Clases\Utilerias;
use App\Empleado;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller {
	//
	use AuthenticatesUsers;

	protected $loginView = 'docente.login';
	protected $guard = "docente";
	protected $username = "usuario";

	public function authenticated() {
		return redirect('/docente/dashboard');
	}

	public function index() {
		return view('docente.dashboard');
	}

	/**
	 * @param Request $request
	 * @return string
	 */
	public function loginDocenteApp(Request $request) {
		if ($request->isJson()) {
			$input = $request->all();
			$objeto = (object)$input[0];
			//hacemos la validacion del login
			if (Auth::guard('docente')->once(['usuario' => $objeto->user, 'password' => $objeto->password])) {
				//
				$empleados = new Empleados();
				$empleados->getSingleEmpleado(Auth::guard('docente')->user()->empleado_id);
				return json_encode([["response" => "Success"], [Auth::guard('docente')->user()]
					,$empleados->getDetalleEmpleados(Auth::guard('docente')->user()->empleado_id)]);
			} else {
				return json_encode([["response" => "Acceso Denegado."]]);
			}
		} else {
			return json_encode([['response' => "ÑO"]]);
		}

	}

	public function asistenciaDocente(Request $request) {
		if ($request->isJson()) {
			$input = $request->all();
			$objeto = (object)$input[0];
			$idEmpleado = Crypt::decrypt($objeto->id);
			$salon = $objeto->salon;
			$idEmpleado2 = $objeto->user;
			if (intval($idEmpleado) === intval($idEmpleado2)){
				$empleados = new Empleados();
				$empleados->getSingleEmpleado($idEmpleado);

				$fechaTomada = date('Y-m-d');
				$diaConsultar = Utilerias::getDiaDB($fechaTomada);
				$plantel = $empleados->getCctPlantel();
				//Buscamos la asignacion de horario del docente
				if($plantel == 1)
					$horarios = Horarios::getHorariClase2($empleados->getId(), $diaConsultar, $salon);
				else
					$horarios = Horarios::getHorariClase($empleados->getId(), $diaConsultar);
				$asistencia = new Asistencias();
				//$horarios = AsignacionHorario::getHorarioPersonalDia(, );
				//se encontro algun horario para este docente
				$horarioActual = date("Y-m-d G:i:s");
				$valor = 0;
				if (count($horarios) > 0) {
					$hora = date("G:i:s");
					foreach ($horarios as $horario) {
						$compara1 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_entrada));
						$compara2 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_salida));
						$valor = $asistencia->compararHoras($horarioActual, $compara1, $compara2, $horario, $idEmpleado, $hora);
						if($valor == 1 || $valor == 2 ){
							break;
						}
					}
				} else {
					$valor = 0;
				}
				$parametros = ['respuesta' => $valor, 'empleado' => $empleados, 'hora' => $horarioActual];

				return json_encode([$parametros]);
			}else{
				$parametros = ['respuesta' => 6];

				return json_encode([$parametros]);
			}


		} else {
			return json_encode([['response' => "ÑO"]]);
		}
	}

	/**
	 * @param Request $request
	 * @return string
	 */
	public function loginAdminApp(Request $request) {
		if ($request->isJson()) {
			$input = $request->all();
			$objeto = (object)$input[0];
			//hacemos la validacion del login
			if (Auth::once(['email' => $objeto->user, 'password' => $objeto->password])) {
				//
				$empleados = new Empleados();
				$empleados->getSingleEmpleado(Auth::user()->empleado_id);
				return json_encode([["response" => "Success"], [Auth::user()]
					,$empleados->getDetalleEmpleados(Auth::user()->empleado_id)]);
			} else {
				return json_encode([["response" => "Acceso Denegado."]]);
			}
		} else {
			return json_encode([['response' => "ÑO"]]);
		}

	}
	public function asistenciaAdmin(Request $request) {
		if ($request->isJson()) {
			$input = $request->all();
			$objeto = (object)$input[0];
			$idEmpleado = Crypt::decrypt($objeto->id);
			$idEmpleado2 = $objeto->user;
			if (intval($idEmpleado) === intval($idEmpleado2)){
				$empleados = new Empleados();
				$empleados->getSingleEmpleado($idEmpleado);

				$fechaTomada = date('Y-m-d');
				$diaConsultar = Utilerias::getDiaDB($fechaTomada);
				//Buscamos la asignacion de horario del docente
				$horarios = Horarios::getHorarioAdmin($empleados->getId(), $diaConsultar);
				$asistencia = new Asistencias();
				//$horarios = AsignacionHorario::getHorarioPersonalDia(, );
				//se encontro algun horario para este docente
				$horarioActual = date("Y-m-d G:i:s");
				$valor = 0;
				$valor2 = 0;
				if (count($horarios) > 0) {
					$hora = date("G:i:s");
					foreach ($horarios as $horario) {
						$compara1 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_entrada));
						$compara2 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_salida));
						$numeroHoras = $asistencia->comparaHorario($horario->hora_entrada,$horario->hora_salida);
						if ($numeroHoras > 2) {
							//validamos horario admon
							$valor = $asistencia->evaluarAdmon($horarioActual, $compara1, $compara2, $horario, $idEmpleado, $hora);
							$valor2 = $valor;
							if($valor == 1 || $valor == 2){
								break;
							}
							
						}else if ($numeroHoras < 1) {
							# desayunos
							$valor = $asistencia->evaluarAdmonDesayuno($horarioActual, $compara1, $compara2, $horario, $idEmpleado, $hora);
							if($valor == 1 || $valor == 2){
								break;
							}
						}
						
					}
					if ($valor2 == 4 && $valor == 3) {
						$valor =4;
					}
				 } else {
					$valor = 0;
				}
				$parametros = ['respuesta' => $valor, 'empleado' => $empleados, 'hora' => $horarioActual];

				return json_encode([$parametros]);
			}else{
				$parametros = ['respuesta' => 6];

				return json_encode([$parametros]);
			}


		} else {
			return json_encode([['response' => "ÑO"]]);
		}
	}

}
