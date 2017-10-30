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

use App\Http\Requests;

class IphoneController extends Controller
{
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

	public function asistenciaAdmin(Request $request) {
		$idEmpleado = Crypt::decrypt($request->qrcode);			
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($idEmpleado);
		#Para resolver el problema de que Cancún no hace cambio de horario, debe cambiarse a true cuando en Chiapas esté con horario de verano
		$horarioVerano = false;
		$fechaTomada = date('Y-m-d');
		$plantel = $empleado->getCctPlantel();
		#Se suma 1 hora, si en Chiapas ya no está activo el horario de verano
		if($plantel == 3 && $horarioVerano == false)
			$hora = date("G:i:s", strtotime('+1 hours'));
		else
			$hora = date("G:i:s");
		//$hora = date('G:i:s', strtotime('07:00:00'));	
		$diaConsultar = Utilerias::getDiaDB($fechaTomada);
		//Buscamos la asignacion de horario del docente
		$horarios = Horarios::getHorarioAdmin($empleado->getId(), $diaConsultar);
		$asistencia = new Asistencias();
		$valor = 0;
		if (count($horarios) > 0) {
			foreach ($horarios as $horario) {
				$valor = $this->guardarAsistenciaAdmin($idEmpleado, $horario, $hora, $fechaTomada, 2); // 1 - Desayuno | 2 - Normal
				if($valor == 7)
					$valor = $this->guardarAsistenciaAdmin($idEmpleado, $horario, $hora, $fechaTomada, 1); // 1 - Desayuno | 2 - Normal
				if($valor == 1 || $valor == 2 || $valor == 4) 
					break;
			}
		 } else {
			$valor = 0;
		}
		if($valor == 4)
			$valor = 3;
		$respuesta = $valor;
		$salon = null;
		return view('PersonalViews.Horario.checador.checador', compact('respuesta', 'empleado', 'hora', 'salon'));
	}

	public function guardarAsistenciaAdmin($id_empleado, $horarioActual, $horaActual, $fechaActual, $tipoHorario) {
		$tipoHora = 0; // 1 - Llegada, 2 - Salida
		$tipoAsistencia = 0; // 1 - A tiempo, 2 - Retardo, 3 - Falta
		$guardar = new Asistencias();
		$buscarAsistencia = Asistencia::where([['id_asignacion_horario', $horarioActual->idAsignacion], ['fecha', $fechaActual]])->first();

		if($tipoHorario == 2) { // 1 - Desayuno | 2 - Normal
			if($buscarAsistencia != null && $buscarAsistencia->hora_salida == null) {
				$tipoHora = 2;
				$tipoAsistencia = $this->validaAsistenciaAdmin($horaActual, $horarioActual->hora_salida, $tipoHorario, $tipoHora);		
			}
			else {
				$tipoHora = 1;
				$tipoAsistencia = $this->validaAsistenciaAdmin($horaActual, $horarioActual->hora_entrada, $tipoHorario, $tipoHora);
			}
		} else {
			if($buscarAsistencia != null && $buscarAsistencia->hora_salida == null) {
				$tipoHora = 2;
				$tipoAsistencia = $this->validaDesayuno($horaActual, $horarioActual->hora_entrada, $tipoHorario, $tipoHora);
			} else {
				$tipoHora = 1; 
				$tipoAsistencia = $this->validaDesayuno($horaActual, $horarioActual->hora_salida, $tipoHorario, $tipoHora);
			}
		}
		//echo '<br>tipoAsistencia:'.$tipoAsistencia.',id_asignacion_horario:'.$horarioActual->idAsignacion;
		// Se registra la asistencia si es puntual, retardo o falta
		if($tipoAsistencia == 1 || $tipoAsistencia == 2 || $tipoAsistencia == 4)
			$checkGuardado = $guardar->checkAsistencia($id_empleado, $tipoHora, $horarioActual->idAsignacion, $horaActual, $tipoAsistencia, 1);
		return $tipoAsistencia;
	}

	public function validaAsistenciaAdmin($horaChecado, $horaES, $tipoHorario, $tipoHora)
	{
		$tolerancia = (strtotime($horaChecado) - strtotime($horaES));
		//echo '<br> horaChecado:'.$horaChecado.', horaES:'.$horaES.', tolerancia='.$tolerancia.', tipoHorario='.$tipoHorario.', tipoHora='.$tipoHora;
		$validar = 0;

		if($tipoHorario	== 2) { // Si es horario normal
			if($tolerancia >= -600 && $tolerancia <=600) {
				//echo "<br> A tiempo";
				$validar = 1;
			}
			else if($tolerancia > 600 && $tolerancia <= 1200) {
				//echo "<br> Retardo";
				$validar = 2;
			}
			else if($tolerancia >= 1201 && $tipoHora == 1) {
				//echo "<br> Entrada Fuera de tiempo";
				$validar = 4;
			}
			else if($tolerancia >= 0 && $tipoHora == 2) {
				//echo "<br> Salida A tiempo";
				$validar = 1;
			}
			else
				$validar = 7;
		}
		return $validar;
	}

	public function validaDesayuno($horaChecado, $horaES, $tipoHorario, $tipoHora)
	{
		$validar = 0;
		$tolerancia = (strtotime($horaChecado) - strtotime($horaES));
		//echo '<br> Desayuno-horaChecado:'.$horaChecado.', horaES:'.$horaES.', tolerancia='.$tolerancia.', tipoHorario='.$tipoHorario.', tipoHora='.$tipoHora;
		if($tolerancia >=-1200 && $tolerancia <= 1200) {
			//echo "<br> Desayuno A tiempo";
			$validar = 1;
		}
		return $validar;
	}

	public function asistenciaDocente(Request $request) {
		$idEmpleado = Crypt::decrypt($request->qrcode);
		$salon = $request->numsalon;
		//echo 'idempleado: '.$idEmpleado.', salon:'.$salon;
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($idEmpleado);
		$fechaTomada = date('Y-m-d');
		$diaConsultar = Utilerias::getDiaDB($fechaTomada);
		#Para resolver el problema de que Cancún no hace cambio de horario, debe cambiarse a true cuando en Chiapas esté con horario de verano
		$horarioVerano = false;
		//$diaConsultar = 1; //para pruebas
		$plantel = $empleado->getCctPlantel();
		//Buscamos la asignacion de horario del docente
		if($plantel == 1)
			$horarios = Horarios::getHorariClase2($empleado->getId(), $diaConsultar, $salon);
		else
			$horarios = Horarios::getHorariClase($empleado->getId(), $diaConsultar);
		$asistencia = new Asistencias();
		//se encontro algun horario para este docente
		$horarioActual = date("Y-m-d G:i:s");
		$valor = 0;
		#Se suma 1 hora, si en Chiapas ya no está activo el horario de verano
		if($plantel == 3 && $horarioVerano == false)
			$hora = date("G:i:s", strtotime('+1 hours'));
		else
			$hora = date("G:i:s");
		if (count($horarios) > 0) {
			//para realizar pruebas **************************************************
			//$hora = date('G:i:s', strtotime('09:00:00'));
			//$fechaTomada = date('Y-m-d', strtotime('17-10-2017'));
			//$horarios = Horarios::getHorariClase($empleado->getId(), $diaConsultar);
			//seccion de pruebas    **************************************************
			foreach ($horarios as $horario) {
				$valor = $this->guardarAsistenciaDocente($idEmpleado, $horario, $hora, $fechaTomada);
				if($valor == 1 || $valor == 2 || $valor == 4)
					break;
			}
		} else {
			$valor = 0;
		}
		switch ($valor) {
			case 0:
				$respuesta = 5; break;
			case 1:
				$respuesta = 2; break; // Sium 1-Retardo | App 2-Retardo
			case 2:
				$respuesta = 1; break; // Sium 2-Atiempo | App 1-Atiempo
			case 5:
				$respuesta = 4; break; // La hora ya fue registrada
			default:
				$respuesta = 3; break;
		}
		return view('PersonalViews.Horario.checador.checador', compact('respuesta', 'empleado', 'hora', 'salon'));
	}

	public function guardarAsistenciaDocente($id_empleado, $horarioActual, $horaActual, $fechaActual)
	{
		$buscarAsistencia = Asistencia::where([['id_asignacion_horario', $horarioActual->id_asignacion_horario], ['fecha', $fechaActual]])->first();
		$guardar = new Asistencias();
		$duracion = $guardar->comparaHorario($horarioActual->hora_entrada , $horarioActual->hora_salida); // se identifica si la clase dura 1 hr o más
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

		//echo '>>>'.$buscarAsistencia->hora_llegada;
		//En caso de que el tipo de asistencia no coincida con lo anterior es posible que ya se haya registrado la hora 
		if($tipoAsistencia == 3 && $buscarAsistencia != null)
		{
			if($buscarAsistencia->hora_llegada != null || $buscarAsistencia->hora_salida != null)
				$tipoAsistencia = 5;
		}
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
}