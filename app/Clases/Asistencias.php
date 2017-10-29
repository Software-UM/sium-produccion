<?php
/**
 * Created by PhpStorm.
 * User: OSORIO
 * Date: 28/08/2016
 * Time: 02:11 PM
 */

namespace app\Clases;

use App\Asistencia;
use Illuminate\Database\QueryException;
use App\Clases\Horarios;
use App\Clases\Parametro;
use App\Clases\TipoHorario;
use App\Clases\AsignacionHorario;

/**
 * Class Asistencias
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Asistencias {

	private $id;
	private $horaLlegada;
	private $horaSalida;
	private $fecha;
	private $estado;
	private $idEmpleado;
	private $idAsignacionHorario;
	private $idEstado;

	/**
	 * Asistencias constructor.
	 * inicializa la clase con valores vacios
	 */
	public function __construct() {
		$this->id = 0;
		$this->horaLlegada = null;
		$this->horaSalida = null;
		$this->fecha = date('Y-m-d');
		$this->estado = 0;
		$this->idEmpleado = 0;
		$this->idAsignacionHorario = 0;
		$this->idEstado = 0;
	}

	/**
	 * Inicializa la clase con valores mandados por el usuario
	 * @param $id
	 * @param $horaLlegada
	 * @param $horaSalida
	 * @param $fecha
	 * @param $estado
	 * @param $idEmpleado
	 * @param $idAsignacionHorario
	 * @param $idEstado
	 * @return Asistencias
	 */
	public function withData($id, $horaLlegada, $horaSalida, $fecha, $estado, $idEmpleado, $idAsignacionHorario, $idEstado) {
		$instance = new self();
		$instance->id = $id;
		$instance->horaLlegada = $horaLlegada;
		$instance->horaSalida = $horaSalida;
		$instance->fecha = $fecha;
		$instance->estado = $estado;
		$instance->idEmpleado = $idEmpleado;
		$instance->idAsignacionHorario = $idAsignacionHorario;
		$instance->idEstado = $idEstado;
		return $instance;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getHoraLlegada() {
		return $this->horaLlegada;
	}

	/**
	 * @param string $horaLlegada
	 */
	public function setHoraLlegada($horaLlegada) {
		$this->horaLlegada = $horaLlegada;
	}

	/**
	 * @return string
	 */
	public function getHoraSalida() {
		return $this->horaSalida;
	}

	/**
	 * @param string $horaSalida
	 */
	public function setHoraSalida($horaSalida) {
		$this->horaSalida = $horaSalida;
	}

	/**
	 * @return false|string
	 */
	public function getFecha() {
		return $this->fecha;
	}

	/**
	 * @param false|string $fecha
	 */
	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	/**
	 * @return int
	 */
	public function getEstado() {
		return $this->estado;
	}

	/**
	 * @param int $estado
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
	}

	/**
	 * @return int
	 */
	public function getIdEmpleado() {
		return $this->idEmpleado;
	}

	/**
	 * @param int $idEmpleado
	 */
	public function setIdEmpleado($idEmpleado) {
		$this->idEmpleado = $idEmpleado;
	}

	/**
	 * @return int
	 */
	public function getIdAsignacionHorario() {
		return $this->idAsignacionHorario;
	}

	/**
	 * @param int $idAsignacionHorario
	 */
	public function setIdAsignacionHorario($idAsignacionHorario) {
		$this->idAsignacionHorario = $idAsignacionHorario;
	}

	/**
	 * @return int
	 */
	public function getIdEstado() {
		return $this->idEstado;
	}

	/**
	 * @param int $idEstado
	 */
	public function setIdEstado($idEstado) {
		$this->idEstado = $idEstado;
	}

	/**
	 * Obtiene la lista de asistencia dependiendo del empleado
	 * @param $idAsignacion
	 * @param $idEmpleado
	 * @param $fecha
	 * @return array
	 */
	public static function getAsistenciaPersonal($idAsignacion, $idEmpleado, $fecha) {
		try {
			$asistencia = Asistencia::where([['id_empleado', $idEmpleado]
				, ['id_asignacion_horario', $idAsignacion],
				['fecha', $fecha]])->get();
			return $asistencia;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la asistencia: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene si hay asistencias para el horario de asignacion
	 * @param $idAsignacion
	 * @return array
	 */
	public static function getAsistenciaHorario($idAsignacion){
		try{
			$asistencia = Asistencia::where("id_asignacion_horario",$idAsignacion)->first();
			return $asistencia["id"] > 0 ? ["error"=>"Ño"] : ["success"=>"Continua"];
		} catch (QueryException $e){
			return ['error' => 'Error al obtener la asistencia: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de asistencia dependiendo del empleado
	 * metodo usado para el chequeo de personal
	 * @param $idEmpleado
	 * @param $idAsignacion
	 * @param $tipoHorario
	 * @param $fecha
	 * @return array
	 */
	public function getAsistencias($idEmpleado, $idAsignacion, $tipoHorario, $fecha) {
		try {
			$asistencia = Asistencia::where([['fecha', $fecha],
				['id_empleado', $idEmpleado],
				['estado', $tipoHorario],
				['id_asignacion_horario', $idAsignacion]])->get();
			return $asistencia;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la asistencia: ' . $e->getMessage()];
		}
	}

	/**
	 * Guarda una nueva asistencia
	 * @param Asistencias $asistencias
	 * @return array
	 */
	public function insertAsistencia(Asistencias $asistencias) {
		try {
			$asistencia = new Asistencia();
			$asistencia->hora_llegada = $asistencias->getHoraLlegada();
			$asistencia->hora_salida = $asistencias->getHoraSalida();
			$asistencia->fecha = $asistencias->getFecha();
			$asistencia->estado = $asistencias->getEstado();
			$asistencia->id_empleado = $asistencias->getIdEmpleado();
			$asistencia->id_asignacion_horario = $asistencias->getIdAsignacionHorario();
			$asistencia->id_estado = $asistencias->getIdEstado();
			$asistencia->chequeo = 2;
			$asistencia->save();
			return ['success' => 'Se agrego la asistencia', 'id' => $asistencia->id];
		} catch (QueryException $e) {
			return ['error' => 'Error al insertar Asistencia: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza la tabla asistencia
	 * @param Asistencias $asistencias
	 * @return array
	 */
	public function updateAsistencia(Asistencias $asistencias) {
		try {
			$asistencia = Asistencia::find($asistencias->getId());
			$asistencia->hora_llegada = $asistencias->getHoraLlegada();
			$asistencia->hora_salida = $asistencias->getHoraSalida();
			$asistencia->fecha = $asistencias->getFecha();
			$asistencia->estado = $asistencias->getEstado();
			$asistencia->id_empleado = $asistencias->getIdEmpleado();
			$asistencia->id_asignacion_horario = $asistencias->getIdAsignacionHorario();
			$asistencia->id_estado = $asistencias->getIdEstado();
			$asistencia->chequeo = 2;
			$asistencia->save();
			return ['success' => 'Se actualizó la asistencia'];
		} catch (QueryException $e) {
			return ['error' => 'Error al actualizar Asistencia: ' . $e->getMessage()];
		}
	}

	/**
	 * Metodo que verifica el chequeo del docente
	 * @param $fechaActual
	 * @param $horaEntrada
	 * @param $horaSalida
	 * @param $horario
	 * @param $idEmpleado
	 * @param $horaChequeo
	 * @return mixed
	 */
	public function compararHoras($fechaActual, $horaEntrada, $horaSalida, $horario, $idEmpleado, $horaChequeo) {
		$valor = 0;
		//obtenemos el tipo de horario y los parametros
		$hor = new Horarios();
		$asignacionHorario = new AsignacionHorario();
		$asignacionHorarios = $asignacionHorario->getAsignacionHorario($horario->id_asignacion_horario);
		$horarios = $hor->getHorario($asignacionHorarios->id_horario);
		$tipo = new TipoHorario();
		$tipos = $tipo->getTipoHorario($horarios->id_tipo_horario);
		$params = new Parametro();
		$parametro = $params->getParametro($tipos->id_parametros);
		$cantidad = $this->comparaHorario($horaEntrada, $horaSalida);
		//validamos si corresponde al tiempo estimado para hacer la asistencia
		$valor = $this->evaluarLimiteDocenteEntrada($fechaActual,$horaEntrada,
			$parametro->tiempo_antes * 60,$parametro->tiempo_despues * 60,$idEmpleado,$horario->id_asignacion_horario,$horaChequeo);
		if ($valor == 125){
			$valor = $this->evaluarLimiteDocenteSalida($fechaActual,$horaSalida,
				$parametro->tiempo_antes * 60,$parametro->tiempo_despues * 60,$idEmpleado,$horario->id_asignacion_horario,$horaChequeo,$cantidad);
		}

		if ($valor == 125) {
			$valor = 3;
		}
		return $valor;
	}

	public function evaluarAdmon($fechaActual, $horaEntrada, $horaSalida, $horario, $idEmpleado, $horaChequeo){
		$hor = new Horarios();
		$asignacionHorario = new AsignacionHorario();
		$asignacionHorarios = $asignacionHorario->getAsignacionHorario($horario->idAsignacion);
		$horarios = $hor->getHorario($asignacionHorarios->id_horario);
		$tipo = new TipoHorario();
		$tipos = $tipo->getTipoHorario($horarios->id_tipo_horario);
		$params = new Parametro();
		$parametro = $params->getParametro($tipos->id_parametros);

		if ($this->evaluarLimite($fechaActual, $horaEntrada, $parametro->tiempo_antes * 60) || $this->evaluarRetardo($fechaActual, $horaEntrada)) {
			//Asistencia de entrada
			if ($this->checkChequeo($idEmpleado, $horario->idAsignacion, 3)){
				$valor = 4;
			}elseif ($this->evaluarLimite($fechaActual, $horaEntrada, $parametro->tiempo_antes * 60)){
				$this->checkAsistencia($idEmpleado,1,$horario->idAsignacion,$horaChequeo,2,3);
				$valor = 1;
			}else{
				//verificamos si hay retardo o es que checo antes de tiempo
				$resultado = $this->evaluarLimiteAdmon($fechaActual,$horaEntrada,$parametro->tiempo_antes * 60,$parametro->tiempo_despues * 60);
				if ($resultado == 1) {
					$this->checkAsistencia($idEmpleado,1,$horario->idAsignacion,$horaChequeo,2,3);
					$valor = 1;
				}else{
					$this->checkAsistencia($idEmpleado,1,$horario->idAsignacion,$horaChequeo,1,3);
					$valor = 2;
				}
			}
		} elseif ($this->evaluarLimite($fechaActual, $horaSalida, $parametro->tiempo_despues * 60) || $this->evaluarLimiteSalida($fechaActual, $horaSalida)) {
			//se checa asistencia de salida
			$this->checkAsistencia($idEmpleado,2,$horario->idAsignacion,$horaChequeo,2,3);
			$valor = 1;
		} else {
			$valor = 3;
		}
		return $valor;
	}

	/**
	 *
	 *
	 */
	public function evaluarAdmonDesayuno($fechaActual, $horaEntrada, $horaSalida, $horario, $idEmpleado, $horaChequeo){
		$hor = new Horarios();
		$asignacionHorario = new AsignacionHorario();
		$asignacionHorarios = $asignacionHorario->getAsignacionHorario($horario->idAsignacion);
		$horarios = $hor->getHorario($asignacionHorarios->id_horario);
		$tipo = new TipoHorario();
		$tipos = $tipo->getTipoHorario($horarios->id_tipo_horario);
		$params = new Parametro();
		$parametro = $params->getParametro($tipos->id_parametros);

		if ($this->evaluarLimite($fechaActual, $horaEntrada, 1200)) {
			//Asistencia de entrada
			if ($this->checkChequeo($idEmpleado, $horario->idAsignacion, 2)){
				$valor = 4;
			}else{
				$this->checkAsistencia($idEmpleado,1,$horario->idAsignacion,$horaChequeo,2,2);
				$valor = 1;
			}
		} elseif ($this->evaluarLimite($fechaActual, $horaSalida,1200)) {
			//se checa asistencia de salida
			$this->checkAsistencia($idEmpleado,2,$horario->idAsignacion,$horaChequeo,2,2);
			$valor = 1;
		} else {
			$valor = 3;
		}
		return $valor;
	}


	public function evaluarLimiteAdmon($fecha, $fecha2,$horaAntes,$horaDespues){
		$correcto = 2;
		$tiempo = ((strtotime($fecha) - strtotime($fecha2))/1);
		if ($tiempo < (-$horaAntes) && $tiempo <=$horaDespues) {
			$correcto  = 1;
		}
		return $correcto;
	}
	/**
	 * Hace la comparacion entra fechas y saca la diferencia dependiendo el limite de tiempo acordado
	 * @param $fecha1
	 * @param $fecha2
	 * @param $tiempo
	 * @return bool
	 */
	public function evaluarLimite($fecha1, $fecha2, $tiempo) {
		$correcto = false;
		$diferencia = abs(strtotime($fecha1) - strtotime($fecha2));
		$limite = ($tiempo * 1000) / 1000;//limite de tiempo
		if ($diferencia <= $limite) {
			$correcto = true;
		}
		return $correcto;
	}

	public function comparaHorario($horaEntrada , $horaSalida){
		$diff = strtotime($horaSalida) - strtotime($horaEntrada);
		return ($diff / 3600);
	}
	/**
	 * Hace la comparacion de fecha y saca la diferencia,
	 * este verifica si esta en el rango de los 15 mins y agrega el retardo
	 * @param $fecha1
	 * @param $fecha2
	 * @return bool
	 */
	public function evaluarRetardo($fecha1, $fecha2) {
		$correcto = false;
		$diferencia = abs(strtotime($fecha1) - strtotime($fecha2));
		$limite = (5400 * 1000) / 1000;//limite de tiempo
		if ($diferencia <= $limite) {
			$correcto = true;
		}
		return $correcto;
	}

	public function evaluarLimiteSalida($fecha1, $fecha2) {
		$correcto = false;
		$diferencia = abs(strtotime($fecha1) - strtotime($fecha2));
		$limite = (7200 * 1000) / 1000;//limite de tiempo
		if ($diferencia <= $limite) {
			$correcto = true;
		}
		return $correcto;
	}

	public function evaluarLimiteDocenteEntrada($fecha1, $fecha2,
												$horaAntes,$horaDespues,
												$idEmpleado,$idAsignacion,
												$horario){
		$correcto = 125;
		$tiempo = ((strtotime($fecha1) - strtotime($fecha2))/1);
		if ($tiempo > (-$horaAntes) && $tiempo <= $horaDespues){
			if($this->checkChequeo($idEmpleado, $idAsignacion, 1)){
				$correcto = 4;
			}else{
				$this->checkAsistencia($idEmpleado,1,$idAsignacion,$horario,2,1);
				$correcto = 1;
			}
		}

		if ($tiempo > $horaDespues && $tiempo <= $horaAntes){
			if($this->checkChequeo($idEmpleado, $idAsignacion, 1)){
				$correcto = 4;
			}else{
				$this->checkAsistencia($idEmpleado,1,$idAsignacion,$horario,1,1);
				$correcto = 2;
			}
		}

		if($tiempo > 600 && $tiempo <= 900){
			if($this->checkChequeo($idEmpleado, $idAsignacion, 1)){
				$correcto = 4;
			}else{
				$this->checkAsistencia($idEmpleado,1,$idAsignacion,$horario,1,1);
				$correcto = 2;
			}
		}

		return $correcto;
	}
	public function evaluarLimiteDocenteSalida($fecha1, $fecha2,
											   $horaAntes,$horaDespues,$idEmpleado,$idAsignacion,$horario,$cantidad){
		$correcto = 125;
		$tiempo = ((strtotime($fecha1) - strtotime($fecha2))/1);
		if ($cantidad > 1){
			$horaAntes = 1200;
		}

		if( $tiempo > (-$horaAntes) && $tiempo <= $horaDespues){
			$this->checkAsistencia($idEmpleado,2,$idAsignacion,$horario,2,1);
			$correcto = 1;
		}
		if ($tiempo > $horaDespues && $tiempo <= 600){
			$this->checkAsistencia($idEmpleado,2,$idAsignacion,$horario,2,1);
			$correcto = 1;
		}

		return $correcto;
	}

	/**
	 * Verifica si ya cuenta con un registro de asistencia el la fecha dada
	 * @param $idEmpleado
	 * @param $idAsignacion
	 * @param $tipoHorario
	 * @return bool
	 */
	public function checkChequeo($idEmpleado, $idAsignacion, $tipoHorario) {
		$check = false;
		try {
			//fecha de hoy
			$hoy = date('Y-m-d');
			$asisistencia = Asistencia::where([['fecha', $hoy],
				['id_empleado', $idEmpleado],
				['id_asignacion_horario', $idAsignacion]])->get();

			if (count($asisistencia) > 0) {
				$check = true;
			} else {
				$check = false;
			}
		} catch (QueryException $e) {
			$check = false;
		}
		return $check;
	}
	/**
	 * Este metodo sirve para verificar si ya hay un regitro previo en la tabla
	 * de asistencia en el caso que sea verdadero este se actualiza de lo
	 * contrario este se inserta
	 *
	 * @param $idEmpleado
	 * @param $tipoEntrada
	 * @param $idAsignacion
	 * @param $horario
	 * @param $estado
	 * @param $tipoHorario
	 * @return bool  1:signifca que es docente 2:desayuno 3:admon
	 */
	public function checkAsistencia($idEmpleado, $tipoEntrada, $idAsignacion, $horario, $estado, $tipoHorario) {
		$check = false;
		$hoy = date('Y-m-d');
		$asistencia = new Asistencias();
		$respuesta = $this->getAsistencias($idEmpleado, $idAsignacion, $tipoHorario, $hoy);
		if (!isset($respuesta['error'])) { //No hubo excepción 
			if (count($respuesta)>0) {
				// se hace el update
				$asistencia->setId($respuesta[0]['id']);
				$asistencia->setHoraLlegada($respuesta[0]['hora_llegada']);
				$asistencia->setHoraSalida($horario);
				$asistencia->setIdEstado($respuesta[0]['id_estado']);
				$asistencia->setEstado($respuesta[0]['estado']);
				$asistencia->setIdEmpleado($idEmpleado);
				$asistencia->setIdAsignacionHorario($idAsignacion);
				$asistencia->setFecha($hoy);
				$response =  $asistencia->updateAsistencia($asistencia);
				if (isset($response['success'])){
					$check = true;
				}else{
					echo $response['error'];
					$check  =false;
				}
			} else {
				// se hace el insert
				$asistencia->setIdEstado($estado);
				$asistencia->setEstado($tipoHorario);
				$asistencia->setIdEmpleado($idEmpleado);
				$asistencia->setIdAsignacionHorario($idAsignacion);
				$asistencia->setFecha($hoy);
				switch ($tipoEntrada) {
					case 1:
						//insert hora de entrada
						$asistencia->setHoraLlegada($horario);
						break;
					case 2:
						//insert hora salida
						$asistencia->setHoraSalida($horario);
						break;
				}
				$response = $asistencia->insertAsistencia($asistencia);
				if (isset($response['success'])){
					$check = true;
				}else{
					echo $response['error'];
					$check = false;
				}
			}
		}

		return $check;
	}

	public function checkAsistenciaDocente($idEmpleado, $tipoEntrada, $idAsignacion, $horario, $estado, $tipoHorario, $fechaHoy) {
		$check = false;
		$asistencia = new Asistencias();
		$respuesta = $this->getAsistencias($idEmpleado, $idAsignacion, $tipoHorario, $fechaHoy);
		if (!isset($respuesta['error'])) { //No hubo excepción 
			if (count($respuesta)>0) {
				// se hace el update
				$asistencia->setId($respuesta[0]['id']);
				$asistencia->setHoraLlegada($respuesta[0]['hora_llegada']);
				$asistencia->setHoraSalida($horario);
				//echo '>>>>TIPO_ASISTENCIA:'.$respuesta[0]['id_estado'];
				switch ($respuesta[0]['id_estado']) {
					case 1:
						if($estado >= 3)
							$asistencia->setIdEstado($estado);
						else 
							$asistencia->setIdEstado($respuesta[0]['id_estado']);
						break;
					case 2:
						$asistencia->setIdEstado($estado);
						break;
					default:
						$asistencia->setIdEstado($respuesta[0]['id_estado']);
						break;
				}
				$asistencia->setEstado($respuesta[0]['estado']);
				$asistencia->setIdEmpleado($idEmpleado);
				$asistencia->setIdAsignacionHorario($idAsignacion);
				$asistencia->setFecha($fechaHoy);
				$response =  $asistencia->updateAsistencia($asistencia);
				if (isset($response['success'])){
					$check = true;
				}else{
					echo $response['error'];
					$check  =false;
				}
			} else {
				// se hace el insert
				$asistencia->setIdEstado($estado);
				$asistencia->setEstado($tipoHorario);
				$asistencia->setIdEmpleado($idEmpleado);
				$asistencia->setIdAsignacionHorario($idAsignacion);
				$asistencia->setFecha($fechaHoy);
				switch ($tipoEntrada) {
					case 1:
						//insert hora de entrada
						$asistencia->setHoraLlegada($horario);
						break;
					case 2:
						//insert hora salida
						$asistencia->setHoraSalida($horario);
						break;
				}
				$response = $asistencia->insertAsistencia($asistencia);
				if (isset($response['success'])){
					$check = true;
				}else{
					echo $response['error'];
					$check = false;
				}
			}
		}

		return $check;
	}
}