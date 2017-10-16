<?php
/**
 * Created by PhpStorm.
 * User: OSORIO
 * Date: 20/05/2017
 * Time: 12:28 PM
 */

namespace app\Clases;


use App\AsignacionClaseAudit;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AsignacionClaseAudition {

	private $id;
	private $hora_entrada;
	private $hora_salida;
	private $dia;
	private $id_ciclo;
	private $id_grupo;
	private $id_carrera;
	private $clave_materia;
	private $id_docente;
	private $id_empleado;
	private $tipo;

	/**
	 * AsignacionClaseAudition constructor.
	 * @param $id
	 * @param $hora_entrada
	 * @param $hora_salida
	 * @param $dia
	 * @param $id_ciclo
	 * @param $id_grupo
	 * @param $id_carrera
	 * @param $clave_materia
	 * @param $id_docente
	 * @param $id_empleado
	 * @param $tipo
	 */
	public function __construct($id, $hora_entrada, $hora_salida, $dia,
								$id_ciclo, $id_grupo, $id_carrera, $clave_materia,
								$id_docente, $id_empleado, $tipo) {
		$this->id = $id;
		$this->hora_entrada = $hora_entrada;
		$this->hora_salida = $hora_salida;
		$this->dia = $dia;
		$this->id_ciclo = $id_ciclo;
		$this->id_grupo = $id_grupo;
		$this->id_carrera = $id_carrera;
		$this->clave_materia = $clave_materia;
		$this->id_docente = $id_docente;
		$this->id_empleado = $id_empleado;
		$this->tipo = $tipo;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getHoraEntrada() {
		return $this->hora_entrada;
	}

	/**
	 * @param mixed $hora_entrada
	 */
	public function setHoraEntrada($hora_entrada) {
		$this->hora_entrada = $hora_entrada;
	}

	/**
	 * @return mixed
	 */
	public function getHoraSalida() {
		return $this->hora_salida;
	}

	/**
	 * @param mixed $hora_salida
	 */
	public function setHoraSalida($hora_salida) {
		$this->hora_salida = $hora_salida;
	}

	/**
	 * @return mixed
	 */
	public function getDia() {
		return $this->dia;
	}

	/**
	 * @param mixed $dia
	 */
	public function setDia($dia) {
		$this->dia = $dia;
	}

	/**
	 * @return mixed
	 */
	public function getIdCiclo() {
		return $this->id_ciclo;
	}

	/**
	 * @param mixed $id_ciclo
	 */
	public function setIdCiclo($id_ciclo) {
		$this->id_ciclo = $id_ciclo;
	}

	/**
	 * @return mixed
	 */
	public function getIdGrupo() {
		return $this->id_grupo;
	}

	/**
	 * @param mixed $id_grupo
	 */
	public function setIdGrupo($id_grupo) {
		$this->id_grupo = $id_grupo;
	}

	/**
	 * @return mixed
	 */
	public function getIdCarrera() {
		return $this->id_carrera;
	}

	/**
	 * @param mixed $id_carrera
	 */
	public function setIdCarrera($id_carrera) {
		$this->id_carrera = $id_carrera;
	}

	/**
	 * @return mixed
	 */
	public function getClaveMateria() {
		return $this->clave_materia;
	}

	/**
	 * @param mixed $clave_materia
	 */
	public function setClaveMateria($clave_materia) {
		$this->clave_materia = $clave_materia;
	}

	/**
	 * @return mixed
	 */
	public function getIdDocente() {
		return $this->id_docente;
	}

	/**
	 * @param mixed $id_docente
	 */
	public function setIdDocente($id_docente) {
		$this->id_docente = $id_docente;
	}

	/**
	 * @return mixed
	 */
	public function getIdEmpleado() {
		return $this->id_empleado;
	}

	/**
	 * @param mixed $id_empleado
	 */
	public function setIdEmpleado($id_empleado) {
		$this->id_empleado = $id_empleado;
	}

	/**
	 * @return mixed
	 */
	public function getTipo() {
		return $this->tipo;
	}

	/**
	 * @param mixed $tipo
	 */
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}



	public function  insert(){
		try {
			$asignacionAudit = new AsignacionClaseAudit();
			$asignacionAudit->hora_entrada = $this->getHoraEntrada();
			$asignacionAudit->hora_salida = $this->getHoraSalida();
			$asignacionAudit->dia = $this->getDia();
			$asignacionAudit->ciclo = $this->getIdCiclo();
			$asignacionAudit->id_grupo = $this->getIdGrupo();
			$asignacionAudit->id_docente = $this->getIdDocente();
			$asignacionAudit->id_empleado = $this->getIdEmpleado();
			$asignacionAudit->id_carrera = $this->getIdCarrera();
			$asignacionAudit->clave_materia = $this->getClaveMateria();
			$asignacionAudit->tipo = $this->getTipo();
			$asignacionAudit->notificado = 1;
			$asignacionAudit->save();
			if ($asignacionAudit->id > 0){
				return ["success" => "Se asigno correctamente la clase"];
			}else{
				return ["error" => "no se guardó"];
			}
		} catch (QueryException $e) {
			return ["error"=>"Error al insertar en audit".$e->getMessage()];
		}
	}

	public static function getNews(){
		try{
			$audi = AsignacionClaseAudit::where("notificado",1)->get();
			return $audi;
		}catch (QueryException $e){
			return ["error"=>"error al ejecutar la consulta de audicion".$e->getMessage()];
		}
	}

	public static function updateNews(){
		try{
			$audi = AsignacionClaseAudit::where("notificado",1)->update(["notificado"=>2]);
			return ["success"=>"Todo bien"];
		}catch (QueryException $e){
			return ["error"=>"error al ejecutar la consulta de audicion".$e->getMessage()];
		}
	}

	public static function getLista(){
		try{
			$audi = AsignacionClaseAudit::join("empleado AS emp","emp.id","=","id_empleado")
				->join("grupos","grupos.id","=","id_grupo")
				->join("empleado AS emp2","emp2.id","=","id_docente")
				->join("materias","materias.clave","=","clave_materia")
				->join("carreras","carreras.id","=","id_carrera")
				->join("ciclos","ciclos.id","=","ciclo")
				->select("hora_entrada","hora_salida","dia","ciclos.nombre_corto",
					"grado","grupo","materias.nombre","carreras.nombre as c",
					DB::raw('CONCAT(emp.nombre,\' \', emp.apellidos) as nombree'),
					DB::raw('CONCAT(emp2.nombre,\' \', emp2.apellidos) as nombred'),
					"tipo","asignacion_clase_audit.created_at as fecha")
				->orderBy("asignacion_clase_audit.created_at","DESC")
				->get();
			return $audi;
		}catch (QueryException $e){
			return ["error"=>"Error al obtener la lista de cambios, ". $e->getMessage()];
		}
	}
}