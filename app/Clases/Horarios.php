<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 14/06/16
 * Time: 08:05 AM
 */

namespace App\Clases;

use App\Horario;
use App\Empleado;
use Illuminate\Database\QueryException;
use DB;

/**
 * Class Horarios
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package App\Clases
 */
class Horarios {

	private $id;
	private $horaEntrada;
	private $horaSalida;
	private $dia;
	private $idTipoHorario;

	/**
	 * Horarios constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->horaEntrada = "";
		$this->horaSalida = "";
		$this->dia = 0;
		$this->idTipoHorario = 0;
	}

	/**
	 * Contructor con datos
	 * @param $id
	 * @param $horaEntrada
	 * @param $horaSalida
	 * @param $dia
	 * @param $idTipoHorario
	 * @return Horarios
	 */
	public static function withData($id, $horaEntrada, $horaSalida, $dia, $idTipoHorario) {
		$instance = new self();
		$instance->id = $id;
		$instance->horaEntrada = $horaEntrada;
		$instance->horaSalida = $horaSalida;
		$instance->dia = $dia;
		$instance->idTipoHorario = $idTipoHorario;
		return $instance;
	}

	/**
	 * @return int
	 */
	public function getIdTipoHorario() {
		return $this->idTipoHorario;
	}

	/**
	 * @param int $idTipoHorario
	 */
	public function setIdTipoHorario($idTipoHorario) {
		$this->idTipoHorario = $idTipoHorario;
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
	public function getHoraEntrada() {
		return $this->horaEntrada;
	}

	/**
	 * @param string $horaEntrada
	 */
	public function setHoraEntrada($horaEntrada) {
		$this->horaEntrada = $horaEntrada;
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
	 * @return int
	 */
	public function getDia() {
		return $this->dia;
	}

	/**
	 * @param int $dia
	 */
	public function setDia($dia) {
		$this->dia = $dia;
	}

	/**
	 * Obtiene la lista de horarios donde no sean docentes
	 * @return Horario
	 */
	public function getHorarios() {
		try {
			$horarios = Horario::join('tipo_horario', 'tipo_horario.id', '=', 'id_tipo_horario')
				->select(DB::raw('DISTINCT (descripcion)'), 'hora_entrada', 'hora_salida', 'tipo_horario.id')
				->where([['descripcion','<>','docente'],['descripcion','<>','DOCENTE']])->get();
			return $horarios;
		} catch (QueryException $e) {
			return ['success' => 'Error al obtener la lista de horarios' . $e->getMessage()];
		}
	}

	/**
	 * Obtenemos un horario
	 * @param $id
	 * @return Horario
	 */
	public function getHorario($id) {
		try {
			$horarios = Horario::find($id);
			return $horarios;
		} catch (QueryException $e) {
			return ['success' => 'Error al obtener la lista de horarios' . $e->getMessage()];
		}
	}

	/**
	 * Se guarda un nuevo horario
	 * @param Horarios $horarios
	 * @return array
	 */
	public function insertHorario(Horarios $horarios) {
		try {
			$horario = new Horario();
			$horario->hora_entrada = $horarios->getHoraEntrada();
			$horario->hora_salida = $horarios->getHoraSalida();
			$horario->dia = $horarios->getDia();
			$horario->id_tipo_horario = $horarios->getIdTipoHorario();
			$horario->save();
			return ['success' => 'Se guardo el registro de horario', 'idHorario' => $horario->id];
		} catch (QueryException $e) {
			return ['success' => 'Error al guardar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza el horario
	 * @param Horarios $horarios
	 * @return array
	 */
	public function updateHorario(Horarios $horarios) {
		try {
			$horario = Horario::find($horarios->getId());
			$horario->hora_entrada = $horarios->getHoraEntrada();
			$horario->hora_salida = $horarios->getHoraSalida();
			$horario->dia = $horarios->getDia();
			$horario->id_tipo_horario = $horarios->getIdTipoHorario();
			$horario->save();
			return ['success' => 'Se actualizo el registro de horario'];
		} catch (QueryException $e) {
			return ['success' => 'Error al actualizar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Elimina el horario
	 * @param $id
	 * @return array
	 */
	public function delete($id) {
		try {
			$horario = Horario::find($id);
			$horario->delete();
			return ['success' => 'Se elimino el registro de horario'];
		} catch (QueryException $e) {
			return ['error' => 'Error al eliminar horario: ' . $e->getMessage()];
		}
	}

	/**
	 * Metodo para obtener el horario asignado, usado en la modificacion de asignacion de horario
	 * @param $idCiclo
	 * @param $horario
	 * @param $idCarrera
	 * @param $idGrupo
	 * @return array
	 */
	public function getHorarioAsignacion($idCiclo, $horario, $idCarrera,$idGrupo) {
		try {
			$horario = Horario::join('asignacion_horario', 'id_horario', '=', 'horario.id')
				->join('empleado', 'empleado.id', '=', 'asignacion_horario.id_empleado')
				->join('asignacion_clase', 'id_asignacion_horario', '=', 'asignacion_horario.id')
				->join('materias', 'materias.clave', '=', 'clave_materias')
				->select('horario.*', 'asignacion_horario.id AS idHorario', 'asignacion_horario.id_empleado',
					DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'),
					'asignacion_clase.id AS idClase', 'asignacion_clase.id_grupos', 'asignacion_clase.clave_materias',
					'materias.nombre as nomMateria')
				->where([['horario.hora_entrada', $horario],
					['id_ciclos', $idCiclo], 
					['id_carreras', $idCarrera],
					['asignacion_horario.activo',true],
					['id_grupos',$idGrupo]])->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios asignados: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de horarios por tipo
	 * @param $idTipo
	 * @return array
	 */
	public function getHorarioIdTipo($idTipo){
		try {
			$horario = Horario::where('id_tipo_horario',$idTipo)->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

	/**
	 * Obtiene los horarios del docente para el dia asignado
	 * @param $idDocente
	 * @param $dia
	 * @return array
	 */
	public static function getHorariClase($idDocente , $dia) {
		try {
			$horario = Horario::join('asignacion_horario','asignacion_horario.id_horario','=','horario.id')
				->join('asignacion_clase','id_asignacion_horario','=','asignacion_horario.id')
				->join('ciclos','id_ciclos','=','ciclos.id')
				->select(DB::raw('MIN(hora_entrada) AS hora_entrada'),DB::raw('MAX(hora_salida) AS hora_salida'),
					DB::raw('MIN(id_asignacion_horario) AS id_asignacion_horario'),'clave_materias','id_grupos', 'id_carreras')
				->where([['id_empleado',$idDocente],
					['dia',$dia],
					['asignacion_horario.activo',true],
					['ciclos.activo',1]])->groupBy('clave_materias','id_grupos', 'id_carreras')->orderBy('hora_entrada')->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

	public static function getHorariClase2($idDocente , $dia, $numero) {
		try {
			$horario = Horario::join('asignacion_horario','asignacion_horario.id_horario','=','horario.id')
				->join('asignacion_clase','id_asignacion_horario','=','asignacion_horario.id')
				->join('ciclos','id_ciclos','=','ciclos.id')
				->join('salon','salon.id','=','id_salon')
				->select(DB::raw('MIN(hora_entrada) AS hora_entrada'),DB::raw('MAX(hora_salida) AS hora_salida'),
					DB::raw('MIN(id_asignacion_horario) AS id_asignacion_horario'),'clave_materias','id_grupos')
				->where([['id_empleado',$idDocente],
					['dia',$dia],
					['numero',$numero],
					['asignacion_horario.activo',true],
					['ciclos.activo',1]])->groupBy('clave_materias','id_grupos')->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

	public static function getHorarioRegistrado($idDocente, $dia, $fecha)
	{
		try {
			$horario = Empleado::join('asistencia as a', 'empleado.id', '=', 'a.id_empleado')
						->join('asignacion_horario as ah', 'a.id_asignacion_horario', '=', 'ah.id')
						->join('horario as ho', 'ah.id_horario', '=', 'ho.id')
						->select('empleado.id', 'a.hora_llegada as hora_entrada', 'a.hora_salida', 'a.fecha', 'ho.dia', 'a.id_asignacion_horario')
						->where([['empleado.id', $idDocente],
							['ho.dia', $dia],
							['a.fecha', $fecha]])->orderBy('a.fecha', 'a.hora_llegada')->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

	public static function getHorarioAdmin($idEmpleado,$dia){
		try{
			$horario = Horario::join('asignacion_horario','asignacion_horario.id_horario','=','horario.id')
			->join('tipo_horario','tipo_horario.id','=','id_tipo_horario')
			->join('parametros','parametros.id','=','id_parametros')
			->select('horario.*','tiempo_antes','tiempo_despues','asignacion_horario.id AS idAsignacion')
			->where([['id_empleado',$idEmpleado],
				['dia',$dia],
				['asignacion_horario.activo',true]])->orderBy('hora_entrada')->get();
			return $horario;
		}catch(QueryException $e){
			return ["error"=>"error al obtener los horarios: ".$e->getMessage()];
		}
	}

	/**
	 * Obtiene toda la carga academica del docente por el ciclo que esta activo
	 * @param $idDocente
	 * @return array
	 */
	public static function getCargaAcademica($idDocente,$idciclo){
		try {
			$horario = Horario::join('asignacion_horario','asignacion_horario.id_horario','=','horario.id')
				->join('asignacion_clase','id_asignacion_horario','=','asignacion_horario.id')
				->join('ciclos','ciclos.id','=','id_ciclos')
				->select(DB::raw('MIN(hora_entrada) AS hora_entrada'),DB::raw('MAX(hora_salida) AS hora_salida'),
					DB::raw('MIN(id_asignacion_horario) AS id_asignacion_horario'),'clave_materias','id_grupos','id_carreras','dia')
				->where([['id_empleado',$idDocente],
					['asignacion_horario.activo',true],
					['ciclos.id',$idciclo]])->groupBy('clave_materias','id_grupos','id_carreras','dia')
				->orderBy('id_grupos','clave_materias', 'desc')->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

	public static function obtCargaAcademica($idDocente, $idciclo)
	{
		try {
			$horario = DB::table('vw_carga_docente')
						->select(DB::raw('CONCAT(clave_materias, id_carreras, id_grupos) AS clv_materia'), 'nom_materia', 'nom_carrera', 
							'id_grupos', 'nom_grupo', 'dia', 'hora_entrada', 'hora_salida', 'id_carreras', 'clave_materias',
							DB::raw('(extract(hour from hora_salida) - extract(hour from hora_entrada)) AS horas'))
						->where([
							['id_empleado', $idDocente],
							['id_ciclo', $idciclo]
						])->get();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios (obtCargaAcademica): '.$e->getMessage()];
		}
	}

	public static function obtHoras($idDocente, $idciclo, $clvMateria)
	{
		try {
			$horario = DB::select('SELECT clv_mat, nom_materia, SUM(horas) totalhrs, id_grupos
						FROM ( SELECT CONCAT(v.clave_materias, v.id_carreras, v.id_grupos) AS clv_mat, v.nom_materia, v.nom_carrera, 
							   v.id_grupos, v.nom_grupo, v.dia, v.hora_entrada, v.hora_salida, 
							   (EXTRACT(HOUR FROM v.hora_salida) - EXTRACT(HOUR FROM v.hora_entrada)) AS horas
						FROM vw_carga_docente v
						WHERE v.id_empleado = '.$idDocente.' AND v.id_ciclo = '.$idciclo.') x
						WHERE clv_mat = \''.$clvMateria.'\'
						GROUP BY clv_mat, nom_materia, id_grupos');
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios (obtCargaAcademica): '.$e->getMessage()];
		}
	}

	public static function obtCargaDocente($idDocente, $idciclo)
	{
		try {
			$horario = DB::select('SELECT clv_mat, nom_materia, SUM(horas) totalhrs, id_grupos
						FROM ( SELECT CONCAT(v.clave_materias, v.id_carreras, v.id_grupos) AS clv_mat, v.nom_materia, v.nom_carrera, 
							   v.id_grupos, v.nom_grupo, v.dia, v.hora_entrada, v.hora_salida, 
							   (EXTRACT(HOUR FROM v.hora_salida) - EXTRACT(HOUR FROM v.hora_entrada)) AS horas
						FROM vw_carga_docente v
						WHERE v.id_empleado = '.$idDocente.' AND v.id_ciclo = '.$idciclo.') x
						GROUP BY clv_mat, nom_materia, id_grupos');
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios (obtCargaAcademica): '.$e->getMessage()];
		}
	}

	/**
	 * Obtiene toda la carga academica del docente por el ciclo que esta activo
	 * @param $idDocente
	 * @param $idAsignacionHorario
	 * @return array
	 */
	public static function getCargaAcademicaSingle($idDocente,$idAsignacionHorario,$ciclo){
		try {
			$horario = Horario::join('asignacion_horario','asignacion_horario.id_horario','=','horario.id')
				->join('asignacion_clase','id_asignacion_horario','=','asignacion_horario.id')
				->join('ciclos','ciclos.id','=','id_ciclos')
				->select(DB::raw('MIN(hora_entrada) AS hora_entrada'),DB::raw('MAX(hora_salida) AS hora_salida'),
					DB::raw('MIN(id_asignacion_horario) AS id_asignacion_horario'),'clave_materias','id_grupos','id_carreras','dia')
				->where([['id_empleado',$idDocente],
					['ciclos.id',$ciclo],
					['asignacion_horario.activo',true],
					['id_asignacion_horario',$idAsignacionHorario]])->groupBy('clave_materias','id_grupos','id_carreras','dia')
				->orderBy('id_grupos','clave_materias', 'desc')->first();
			return $horario;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener los horarios: '.$e->getMessage()];
		}
	}

}