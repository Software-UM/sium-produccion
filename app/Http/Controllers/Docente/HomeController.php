<?php

namespace App\Http\Controllers\Docente;

use App\Asistencia;
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
				//$diaConsultar = 6; //para pruebas
				$plantel = $empleados->getCctPlantel();
				//Buscamos la asignacion de horario del docente
				if($plantel == 1)
					$horarios = Horarios::getHorariClase2($empleados->getId(), $diaConsultar, $salon);
				else
					$horarios = Horarios::getHorariClase($empleados->getId(), $diaConsultar);
				$asistencia = new Asistencias();
				$buscaAsistencia = new Asistencias();
				//se encontro algun horario para este docente
				$horarioActual = date("Y-m-d G:i:s");
				$valor = 0;
				if (count($horarios) > 0) {
					$hora = date("G:i:s");
					//para realizar pruebas
					/*$fechaTomada = date('Y-m-d', strtotime('15-10-2017'));
					$diaConsultar = Utilerias::getDiaDB($fechaTomada);
					$horarioActual = date('Y-m-d G:i:s', strtotime('15-10-2017 13:40:01'));
					$hora = date('G:i:s', strtotime('13:40:01'));
					$horarios = Horarios::getHorariClase($empleados->getId(), $diaConsultar);*/
					//seccion de pruebas
					foreach ($horarios as $horario) {
						$valor = $this->guardarAsistenciaDocente($idEmpleado, $horario, $hora, $fechaTomada);
						/* original
						$compara1 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_entrada));
						$compara2 = date('Y-m-d G:i:s', strtotime($fechaTomada . " " . $horario->hora_salida));
						$valor = $asistencia->compararHoras($horarioActual, $compara1, $compara2, $horario, $idEmpleado, $hora);
						*/
						if($valor == 1 || $valor == 2 || $valor == 4){
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
			return json_encode([['response' => "NO"]]);
		}
	}

	public function guardarAsistenciaDocente($id_empleado, $horarioActual, $horaActual, $fechaActual)
	{
		$buscarAsistencia = Asistencia::where([['id_asignacion_horario', $horarioActual->id_asignacion_horario], ['fecha', $fechaActual]])->first();
		$guardar = new Asistencias();
		$duracion = $guardar->comparaHorario($horarioActual->hora_entrada , $horarioActual->hora_salida); // se identifica si la clase dura 1 hr o más
		echo '<br>id_asignacion_horario: '.$horarioActual->id_asignacion_horario.', fechaActual: '.$fechaActual;

		$tipoHora = 0; // 1-Llegada, 2-Salida
		$tipoAsistencia = 0; // 1-Retardo, 2-Asistencia

		if($buscarAsistencia != null && $buscarAsistencia->hora_salida == null) { // Se verifica que existe un registro de asistencia y que no tenga hora de salida
			$tipoHora = 2;
			// validar límites para identificar si es asistencia, retardo o falta(no se registra).
			$tipoAsistencia = $this->validaAsistencia($horaActual, $horarioActual->hora_salida, $duracion, $tipoHora);		
		}
		else { // No hay hora de entrada registrada
			$tipoHora = 1;
			// validar límites para identificar si es asistencia, retardo o falta(no se registra).
			$tipoAsistencia = $this->validaAsistencia($horaActual, $horarioActual->hora_entrada, $duracion, $tipoHora);
		}

		// Se registra la asistencia
		if($tipoAsistencia == 1 || $tipoAsistencia == 2 || $tipoAsistencia == 4) // Se registra la asistencia si es retardo o en tiempo
			$checkGuardado = $guardar->checkAsistenciaDocente($id_empleado, $tipoHora, $horarioActual->id_asignacion_horario, $horaActual, $tipoAsistencia, 1, $fechaActual);
		return $tipoAsistencia;
	}

	public function validaAsistencia($horaChecado, $horaES, $duracion, $tipoHora)
	{
		$tolerancia = (strtotime($horaChecado) - strtotime($horaES));
		//echo '<br> horaChecado:'.$horaChecado.', horaES:'.$horaES.', tolerancia='.$tolerancia.', duracion='.$duracion.', tipoHora='.$tipoHora;
		
		if($tolerancia >= -600 && $tolerancia <= 600) { //Hora entrada/salida - Asistencia
			//echo '<br>condicion 1';
			return 2;
		}
		else if($tolerancia > 600 && $tolerancia <= 1200) {//Hora entrada/salida - Retardo
			//echo '<br>condicion 2';
			return 1;
		}
		else if($tolerancia >= -1200 && $tolerancia <= 600 && $duracion >= 2 && $tipoHora == 2) {//Hora salida - Asistencia Clases con +2 horas 
			//echo '<br> condicion 3';
			return 2;
		}
		else if($tolerancia >= 1201 && $tolerancia <= 2999) //Hora entrada/salida - Falta
		{
			//echo '<br> Es falta';
			return 4;
		}
		return 3;
		
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
