<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 28/06/16
 * Time: 12:52 PM
 */

namespace app\Clases;

use App\AsignacionHorarios;
use App\Clases\Ciclo;
use App\Clases\AsignacionClase;
use App\Clases\Horarios;
use App\Clases\AsignacionClaseAudition;
use Illuminate\Database\QueryException;

/**
 * Class AsignacionHorario
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class AsignacionHorario {
	private $id;
	private $fecha;
	private $idHorario;
	private $idEmpleado;

	/**
	 * AsignacionHorario constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->fecha = "";
		$this->idHorario = 0;
		$this->idEmpleado = 0;
	}

	/**
	 * Inicializa la clase con valores mandados por el usuario
	 * @param $id
	 * @param $fecha
	 * @param $idHorario
	 * @param $idEmpleado
	 * @return AsignacionHorario
	 */
	public static function withData($id, $fecha, $idHorario, $idEmpleado) {
		$instance = new self();
		$instance->id = $id;
		$instance->fecha = $fecha;
		$instance->idHorario = $idHorario;
		$instance->idEmpleado = $idEmpleado;
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
	public function getFecha() {
		return $this->fecha;
	}

	/**
	 * @param string $fecha
	 */
	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	/**
	 * @return int
	 */
	public function getIdHorario() {
		return $this->idHorario;
	}

	/**
	 * @param int $idHorario
	 */
	public function setIdHorario($idHorario) {
		$this->idHorario = $idHorario;
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

	public function getActivo()
	{
		return $this->activo;
	}

	public function setActivo($activo)
	{
		$this->activo = $activo;
	}

	/**
	 * Obtiene la lista general de los horarios asignados
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAsignacionHorarios() {
		try {
			$asignacionHorario = AsignacionHorarios::all();
			return $asignacionHorario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de horarios: ' . $e->getMessage()];
		}
	}

	/**
	 * Verifica si el docente tiene un horario asignado a la misma hora el mismo dia y el mismo ciclo
	 * @param $idEmpleado
	 * @param $fechaInicio
	 * @param $fechaFin
	 * @param $dia
	 * @param $ciclo
	 * @return array
	 */
	public function getAsignacion($idEmpleado, $fechaInicio, $fechaFin, $dia, $ciclo) {
		try {
			$asignacion = AsignacionHorarios::join('horario', 'horario.id', '=', 'id_horario')
				->select('asignacion_horario.id AS id')
				->where([['hora_entrada', $fechaInicio], ['hora_salida', $fechaFin],
					['id_empleado', $idEmpleado], ['dia', $dia], ['activo', true]])
				->get();
			/*	foreach ($asignacion as $item){
					print_r($item->id);echo "<br>";
				}*/
			if (count($asignacion) > 0) {
				foreach ($asignacion as $asigEmp) {
					$asignacionClase = new AsignacionClase();
					$response2 = $asignacionClase->findGrupoAsigandoEmp($ciclo, $asigEmp->id);
					return $response2;
				}

			} else {
				return $asignacion;
			}
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de horarios: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la asignacion de horario por id
	 * @param $id
	 * @return array
	 */
	public function getAsignacionHorario($id) {
		try {
			$asignacionHorario = AsignacionHorarios::find($id);
			return $asignacionHorario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de horarios: ' . $e->getMessage()];
		}
	}

	/**
	 * Inserta una nueva asignacion de horario
	 * @param AsignacionHorario $asignacionHorario
	 * @return array
	 */
	public function insertAsignacionHorario(AsignacionHorario $asignacionHorario) {
		try {
			$asignacion = new AsignacionHorarios();
			$asignacion->fecha = $asignacionHorario->getFecha();
			$asignacion->id_horario = $asignacionHorario->getIdHorario();
			$asignacion->id_empleado = $asignacionHorario->getIdEmpleado();
			$asignacion->save();
			return ['success' => 'Asignacion correcta', 'idAsignacion' => $asignacion->id];
		} catch (QueryException $e) {
			return ['error' => 'Error al asignar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza la asignacion de horario
	 * @param AsignacionHorario $asignacionHorario
	 * @return array
	 */
	public function updateAsignacionHorario(AsignacionHorario $asignacionHorario) {
		try {
			$asignacion = AsignacionHorarios::find($asignacionHorario->getId());
			$asignacion->fecha = $asignacionHorario->getFecha();
			$asignacion->id_horario = $asignacionHorario->getIdHorario();
			$asignacion->id_empleado = $asignacionHorario->getIdEmpleado();
			$asignacion->save();
			return ['success' => 'Asignacion modificada'];
		} catch (QueryException $e) {
			return ['error' => 'Error al modificar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Elimina la asignacion de horario
	 * @param $id
	 * @return array
	 */
	public function deleteAsignacionHorario($id) {
		try {
			$asignacion = AsignacionHorarios::find($id);
			$asignacion->activo = false;
			$asignacion->save();
			//$asignacion->delete();
			return ['success' => 'Asignacion Horario eliminada'];
		} catch (QueryException $e) {
			return ['error' => 'Error al eliminar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza la asignacion de horario agregando un nuevo empleado
	 * @param $idHorario
	 * @param $idEmpleado
	 * @return array
	 */
	public function updateAsignacionEmpleado($idHorario, $idEmpleado, $idUser) {
		try {
			//hacemos el guardado en nuestro log
			$ciclo = new Ciclo();
			$ciclos = $ciclo->getCicloActivo();
			$asignacion = AsignacionHorarios::find($idHorario);
			/*$horario = Horarios::getCargaAcademicaSingle($asignacion->id_empleado,$idHorario);
			$audit = new AsignacionClaseAudition(0,$horario->hora_entrada,
				$horario->hora_salida,$horario->dia,$ciclos[0]["id"],
				$horario->id_grupos,$horario->id_carreras,
				$horario->clave_materias,$asignacion->id_empleado,$idUser,3);
			$response  = $audit->insert();
			$audit2 = new AsignacionClaseAudition(0,$horario->hora_entrada,
				$horario->hora_salida,$horario->dia,$ciclos[0]["id"],
				$horario->id_grupos,$horario->id_carreras,
				$horario->clave_materias,$idEmpleado,$idUser,2);
			$response  = $audit2->insert();*/
			$asignacion->id_empleado = $idEmpleado;
			$asignacion->save();
			return ['success' => 'Asignacion Horario modificada'];
		} catch (QueryException $e) {
			return ['error' => 'Error al actualizar horario'];
		}
	}

	/**
	 * Obtiene la lista de horarios del empleado sea docente u otro tipo de personal
	 * @param $idEmpleado
	 * @return array
	 */
	public function getHorarioPersonal($idEmpleado) {
		try {
			$asignacion = AsignacionHorarios::join('horario', 'horario.id', '=', 'id_horario')
				->join('tipo_horario', 'tipo_horario.id', '=', 'id_tipo_horario')
				->select('horario.*', 'tipo_horario.descripcion','asignacion_horario.id as idA')
				->where('id_empleado', $idEmpleado)
				->where('activo', true)
				->orderBy('dia', 'asc')->get();
			return $asignacion;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener las lista de horarios: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de horarios del empleado por día
	 * @param $dia
	 * @param $idEmpleado
	 * @return array
	 */
	public static function getHorarioPersonalDia($dia, $idEmpleado){
		try {
			$asignacion = AsignacionHorarios::join('horario', 'horario.id', '=', 'id_horario')
				->select('horario.*','asignacion_horario.id as idA')
				->where([['id_empleado', $idEmpleado],['dia',$dia],['activo', true]])->get();
			return $asignacion;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener las lista de horarios: ' . $e->getMessage()];
		}
	}
}