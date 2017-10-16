<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 28/06/16
 * Time: 02:09 PM
 */

namespace app\Clases;

use App\AsignacionClases;
use Illuminate\Database\QueryException;
use DB;

/**
 * Class AsignacionClase
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class AsignacionClase {
	private $id;
	private $idAsignacionHorario;
	private $claveMaterias;
	private $idCiclos;
	private $idCarreras;
	private $idGrupo;
	private $salon;

	/**
	 * AsignacionClase constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->idAsignacionHorario = 0;
		$this->claveMaterias = "";
		$this->idCiclos = 0;
		$this->idCarreras = 0;
		$this->idGrupo = 0;
		$this->salon = 0;
	}

	/**
	 * Inicializa la clase con valores mandados por el usuario
	 * @param $id
	 * @param $idGrupo
	 * @param $idAsignacionHorario
	 * @param $claveMaterias
	 * @param $idCiclos
	 * @param $idCarreras
	 * @return AsignacionClase
	 */
	public static function withData($id, $idGrupo, $idAsignacionHorario, $claveMaterias, $idCiclos, $idCarreras,$salon) {
		$instance = new self();
		$instance->id = $id;
		$instance->idGrupo = $idGrupo;
		$instance->idAsignacionHorario = $idAsignacionHorario;
		$instance->claveMaterias = $claveMaterias;
		$instance->idCiclos = $idCiclos;
		$instance->idCarreras = $idCarreras;
		$instance->salon = $salon;
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
	 * @return string
	 */
	public function getClaveMaterias() {
		return $this->claveMaterias;
	}

	/**
	 * @param string $claveMaterias
	 */
	public function setClaveMaterias($claveMaterias) {
		$this->claveMaterias = $claveMaterias;
	}

	/**
	 * @return int
	 */
	public function getIdCiclos() {
		return $this->idCiclos;
	}

	/**
	 * @param int $idCiclos
	 */
	public function setIdCiclos($idCiclos) {
		$this->idCiclos = $idCiclos;
	}

	/**
	 * @return int
	 */
	public function getIdCarreras() {
		return $this->idCarreras;
	}

	/**
	 * @param int $idCarreras
	 */
	public function setIdCarreras($idCarreras) {
		$this->idCarreras = $idCarreras;
	}

	/**
	 * @return int
	 */
	public function getIdGrupo() {
		return $this->idGrupo;
	}

	/**
	 * @param int $idGrupo
	 */
	public function setIdGrupo($idGrupo) {
		$this->idGrupo = $idGrupo;
	}

	/**
	 * Metodo para obtener todas las clases
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getClases() {
		try {
			$clases = AsignacionClases::all();
			return $clases;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de asignaciones: ' . $e->getMessage()];
		}
	}

	public function setSalon($salon){
		$this->salon = $salon;
	}

	public function getSalon(){
		return $this->salon;
	}

	/**
	 * Funcion para obtener la lista de los grupos con algun asignacion de clase general
	 * @return AsignacionClases;
	 */
	public function getClaseGrupo($cct){
		try{
			$clases = AsignacionClases::join('ciclos AS ci','ci.id','=','asignacion_clase.id_ciclos')
				->join('grupos AS gp','gp.id','=','asignacion_clase.id_grupos')
				->join('carreras AS car','car.id','=','asignacion_clase.id_carreras')
                ->join('plantel','cct_plantel',"=","cct")
				->join('salon','salon.id','=','id_salon')
				->join('edificio','edificio.id','=','id_edificio')
				->select(DB::raw('DISTINCT(gp.*)'),'car.id as id_carrera','car.nombre','ci.nombre_corto',
					'ci.id AS id_ciclo','asignacion_clase.id_salon','salon.numero','edificio.nombre as edificio')
				->where('gp.id_modalidad','<>',2)
                ->where('cct',$cct)
				->where('ci.activo', 1)
				->orderBy('nombre_corto','asc')->get();
			return $clases;
		}catch (QueryException $e){
			return ['error' => 'Error al obtener la lista de asignaciones: ' . $e->getMessage()];
		}
	}
    //TODO agregar a la nueva version
	public function getClaseGrupoSemi($cct){
		try{
			$clases = AsignacionClases::join('ciclos AS ci','ci.id','=','asignacion_clase.id_ciclos')
				->join('grupos AS gp','gp.id','=','asignacion_clase.id_grupos')
				->join('carreras AS car','car.id','=','asignacion_clase.id_carreras')
                ->join('plantel','cct_plantel',"=","cct")
				->join('salon','salon.id','=','id_salon')
				->join('edificio','edificio.id','=','id_edificio')
				->join('asignacion_horario AS asiHor','asiHor.id','=','asignacion_clase.id_asignacion_horario')
				->join('horario','horario.id','=','asiHor.id_horario')
				->select(DB::raw('DISTINCT(gp.*)'),'car.id as id_carrera','car.nombre','ci.nombre_corto',
					'ci.id AS id_ciclo','asignacion_clase.id_salon','salon.numero','edificio.nombre as edificio','dia')
				->where("gp.id_modalidad",2)
                ->where('cct',$cct)
				->where('ci.activo', 1)
				->orderBy('nombre_corto','asc')->get();
			return $clases;
		}catch (QueryException $e){
			return ['error' => 'Error al obtener la lista de asignaciones: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la clases asignadas para el grupo que se quiera consultar escolarizado
	 * @param $idGrupo
	 * @param $idCarrera
	 * @param $idCiclo
	 * @param $idMateria
	 * @return array
	 */
	public function getClasePorGrupo($idGrupo,$idCarrera,$idCiclo,$idMateria){
		try{
			$clases =  AsignacionClases::join('materias AS mat','mat.clave','=','asignacion_clase.clave_materias')
				->join('carreras AS car','car.id','=','asignacion_clase.id_carreras')
				->join('grupos AS gp','gp.id','=','asignacion_clase.id_grupos')
				->join('asignacion_horario AS asiHor','asiHor.id','=','asignacion_clase.id_asignacion_horario')
				->join('horario','horario.id','=','asiHor.id_horario')
				->select(DB::raw('MIN(hora_entrada) AS hora_entrada'),DB::raw('MAX(hora_salida) AS hora_salida'),
					'id_carreras','id_grupos','clave_materias','dia','id_empleado')
				->where([['gp.id',$idGrupo],
					['car.id',$idCarrera],
					['asignacion_clase.id_ciclos',$idCiclo],
					['mat.clave',$idMateria]])
				->groupBy('clave_materias','id_grupos','id_carreras','dia','id_empleado')
				->orderBy("hora_entrada")->get();
			return $clases;
		}catch (QueryException $e){
			return ['error' => 'Error al obtener las materias por grupo: ' . $e->getMessage()];
		}
	}

	public function getEmpleadosCarga(){
		try{
			$clases = AsignacionClases::join('asignacion_horario AS asiHor','asiHor.id','=','asignacion_clase.id_asignacion_horario')
				->join('empleado as emp','emp.id','=','asiHor.id_empleado')
				->select(DB::raw('DISTINCT(emp.id) AS id_empleado'),'emp.nombre','emp.apellidos','id_salon')
				->where([['id_grupos',$this->getIdGrupo()],
					['id_carreras',$this->getIdCarreras()],
					['id_ciclos',$this->getIdCiclos()],['id_salon',$this->getSalon()]])
				->get();
			return $clases;
		}catch (QueryException $e){
			return ['error'=>'Error al obtener los empleados: '. $e->getMessage()];
		}
	}

	public function getEmpleadosCargaDia($dia){
		try{
			$clases = AsignacionClases::join('asignacion_horario AS asiHor','asiHor.id','=','asignacion_clase.id_asignacion_horario')
				->join('empleado as emp','emp.id','=','asiHor.id_empleado')
				->join('horario as hor','hor.id','=','id_horario')
				->select(DB::raw('DISTINCT(emp.id) AS id_empleado'),'emp.nombre','emp.apellidos','id_salon')
				->where([['id_grupos',$this->getIdGrupo()],
					['id_carreras',$this->getIdCarreras()],
					['id_ciclos',$this->getIdCiclos()],
					['id_salon',$this->getSalon()],['hor.dia',$dia]])
				->get();
			return $clases;
		}catch (QueryException $e){
			return ['error'=>'Error al obtener los empleados: '. $e->getMessage()];
		}
	}

	/**
	 * Obtiene la clases asignadas para el grupo que se quiera consultar semiescolarizado
	 * @param $idGrupo
	 * @param $idCarrera
	 * @param $idCiclo
	 * @param $idMateria
	 * @param $dia
	 * @return array
	 */
	public function getClasePorGrupoSemi($idGrupo,$idCarrera,$idCiclo,$idMateria,$dia){
		try{
			$clases =  AsignacionClases::join('materias AS mat','mat.clave','=','asignacion_clase.clave_materias')
				->join('carreras AS car','car.id','=','asignacion_clase.id_carreras')
				->join('grupos AS gp','gp.id','=','asignacion_clase.id_grupos')
				->join('asignacion_horario AS asiHor','asiHor.id','=','asignacion_clase.id_asignacion_horario')
				->join('horario','horario.id','=','asiHor.id_horario')
				->select('asignacion_clase.*','horario.hora_entrada','horario.hora_salida','horario.dia','asiHor.id_empleado')
				->where([['gp.id',$idGrupo],
					['car.id',$idCarrera],
					['asignacion_clase.id_ciclos',$idCiclo],
					['mat.clave',$idMateria],['horario.dia',$dia]])->get();
			return $clases;
		}catch (QueryException $e){
			return ['error' => 'Error al obtener las materias por grupo: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene las materias asignadas al grupo para el ciclo consultado
	 * @param $idGrupo
	 * @param $idCarrera
	 * @param $idCiclo
	 * @return array
	 */
	public function getClaveMateriasGrupo($idGrupo,$idCarrera,$idCiclo){
		try{
			$clases =  AsignacionClases::join('materias AS mat','mat.clave','=','asignacion_clase.clave_materias')
				->join('carreras AS car','car.id','=','asignacion_clase.id_carreras')
				->join('grupos AS gp','gp.id','=','asignacion_clase.id_grupos')
				->select(DB::raw('DISTINCT(asignacion_clase.clave_materias)'),'mat.nombre','car.nombre AS nombre_carrera')
				->where([['gp.id',$idGrupo],
					['car.id',$idCarrera],
					['asignacion_clase.id_ciclos',$idCiclo]])->get();
			return $clases;
		}catch (QueryException $e){
			return ['error' => 'Error al obtener las materias por grupo: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene el objeto asignacion clase
	 * @param $id
	 * @return array
	 */
	public function getClase($id) {
		try {
			$clases = AsignacionClases::find($id);
			return $clases;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de asignaciones: ' . $e->getMessage()];
		}
	}

	/**
	 * Pediente
	 * @param $idDocente
	 * @return array
	 */
	public function getClasesDocente($idDocente) {
		try {
			$clases = AsignacionClases::join()->where('')->get();
			return $clases;
		} catch (QueryException $e) {
			return ['error' => 'Error al obtener la lista de asignaciones: ' . $e->getMessage()];
		}
	}

	/**
	 * Verifica si el grupo ya tiene una asignacion de clases
	 * @param $idGrupo
	 * @param $idCarrera
	 * @param $idCiclo
	 * @return array
	 */
	public function findGrupoAsigando($idGrupo, $idCarrera, $idCiclo) {
		try {
			$clases = AsignacionClases::where([['id_grupos', $idGrupo],
				['id_carreras', $idCarrera],
				['id_ciclos', $idCiclo]])->get();
			return $clases;
		} catch (QueryException $e) {
			return ['error' => 'Error al validar asignaciones: ' . $e->getMessage()];
		}
	}

	/**
	 * Busca si el docente tiene una asignacion de horario en la tabla asignacion clase
	 * @param $idCiclo
	 * @param $idAsignacion
	 * @return array
	 */
	public function findGrupoAsigandoEmp($idCiclo, $idAsignacion) {
		try {
			$clases = AsignacionClases::join('grupos','grupos.id','=','id_grupos')
				->join('carreras','carreras.id','=','id_carreras')
				->select('asignacion_clase.*','grupos.grado','grupos.grupo','carreras.nombre')
				->where([['id_asignacion_horario', $idAsignacion],
				['id_ciclos', $idCiclo]])->get();
			return $clases;
		} catch (QueryException $e) {
			return ['error' => 'Error al validar asignaciones: ' . $e->getMessage()];
		}
	}

	/**
	 * Inserta a nueva clase para el docente
	 * @param AsignacionClase $asignacionClase
	 * @return array
	 */
	public function insertClaseDocente(AsignacionClase $asignacionClase) {
		try {
			$clases = new AsignacionClases();
			$clases->id_grupos = $asignacionClase->getIdGrupo();
			$clases->id_asignacion_horario = $asignacionClase->getIdAsignacionHorario();
			$clases->clave_materias = $asignacionClase->getClaveMaterias();
			$clases->id_ciclos = $asignacionClase->getIdCiclos();
			$clases->id_carreras = $asignacionClase->getIdCarreras();
			$clases->id_salon = $asignacionClase->getSalon();
			$clases->save();
			return ['success' => 'Se asigno correctamente la clase','id_clase'=>$clases->id];
		} catch (QueryException $e) {
			return ['error' => 'Error al guardar Clase docente'.$e->getMessage()];
		}
	}

	/**
	 * Modifica la asignacion de clase para el docente
	 * @param AsignacionClase $asignacionClase
	 * @return array
	 */
	public function updateClaseDocente(AsignacionClase $asignacionClase) {
		try {
			$clases = AsignacionClases::find($asignacionClase->getId());
			$clases->id_grupos = $asignacionClase->getIdGrupo();
			$clases->id_asignacion_horario = $asignacionClase->getIdAsignacionHorario();
			$clases->clave_materias = $asignacionClase->getClaveMaterias();
			$clases->id_ciclos = $asignacionClase->getIdCiclos();
			$clases->id_carreras = $asignacionClase->getIdCarreras();
			$clases->save();
			return ['success' => 'Se modifico correctamente la clase'];
		} catch (QueryException $e) {
			return ['error' => 'Error al modificar Clase docente' . $e->getMessage()];
		}
	}

	/**
	 * Elimina la asignacion de clase del docente
	 * @param $id
	 * @return array
	 */
	public function delete($id) {
		try {
			$clases = AsignacionClases::find($id);
			$clases->delete();
			return ['success' => 'Se elimino correctamente la clase'];
		} catch (QueryException $e) {
			return ['error' => 'Error al eliminar Clase docente' . $e->getMessage()];
		}
	}

	/**
	 * Elimina la asignacion de clases por id de horario asignado
	 * @param $idAsignacion
	 * @return array
	 */
	public function deleteClase($idAsignacion){
		try{
			$clases = AsignacionClases::where('id',$idAsignacion)->delete();
			return ['success' => 'Se elimino correctamente la clase'];
		} catch (QueryException $e){
			return ['error' => 'Error al eliminar Clase docente' . $e->getMessage()];
		}
	}

	public function buscarSalon($idCiclo,$idSalon,$idGrupo,$dia){
		try{
			$clases = AsignacionClases::join("grupos","grupos.id","=","id_grupos")
				->join("asignacion_horario","asignacion_horario.id","=","id_asignacion_horario")
				->join("horario","horario.id","=","id_horario")
				->where([['id_ciclos',$idCiclo],['id_salon',$idSalon],['id_modalidad',$idGrupo],["horario.dia",$dia]])->first();
			if (count($clases)>0){
				return ["error"=>"Salon ocupado"];
			}else{
				return ["success"=>"Grupo disponible"];
			}
		}catch (QueryException $e){
			return ["error"=>"Error al validar".$e->getMessage()];
		}
	}

	public function getAsignacionDia($idCiclo,$idSalon,$mod,$dia,$idCarrera,$idGrupo){
		try{
			$clases = AsignacionClases::join("grupos","grupos.id","=","id_grupos")
				->join("asignacion_horario","asignacion_horario.id","=","id_asignacion_horario")
				->join("horario","horario.id","=","id_horario")
				->select("asignacion_clase.id AS id")
				->where([['id_ciclos',$idCiclo],['id_salon',$idSalon],["id_grupos",$idGrupo],
					['id_modalidad',$mod],["horario.dia",$dia],["id_carreras",$idCarrera]])->get();
			return $clases;
		}catch (QueryException $exception){
			return ["error"=>"Error al validar".$exception->getMessage()];
		}
	}

	public  function updateSalonSingle($id, $salon){
		try {
			$asignaciones = AsignacionClases::where("id",$id)->update(['id_salon'=> $salon]);
			return ["success"=>"Se actualizó el salon con éxito"];
		} catch (QueryException $exception) {
			return ["error","Error al actualizar el salon: ".$exception->getMessage()];
		}
	}
	//FALTA ACTUALIZAR AL SERVER
	public function updateSalon($idCiclo,$idSalon,$idGrupo,$idSalonNuevo,$idCarrera){
		try{
			$asignaciones = AsignacionClases::where("id_grupos",$idGrupo)->where("id_ciclos",$idCiclo)
				->where("id_salon",$idSalon)->where("id_carreras",$idCarrera)
				->update(['id_salon' => $idSalonNuevo]);
			return ["success"=>"Se actualizó el salon con éxito"];
		}catch (QueryException $e){
			return ["error","Error al actualizar el salon".$e->getMessage()];
		}
	}

	public function obtenerFechas($idEmpleado, $idCiclo, $idGrupo, $idCarrera,$idMateria){
		try{
			$asignacionesFecha = AsignacionClases::join("asignacion_horario","asignacion_horario.id",
				"=","id_asignacion_horario")
				->join("fechas_posgrado","fechas_posgrado.id_asignacion_clase","=","asignacion_clase.id")
				->select("asignacion_horario.id AS idAsignacion","asignacion_clase.id AS idClase",
					"asignacion_horario.id_horario AS idHorario","fechas_posgrado.id AS idFechas","fechas_posgrado.fecha")
				->where([["asignacion_horario.id_empleado",$idEmpleado],
					["asignacion_clase.id_grupos",$idGrupo],
					["asignacion_clase.clave_materias",$idMateria],
					["asignacion_clase.id_ciclos",$idCiclo]
					,["asignacion_clase.id_carreras",$idCarrera]])
				->get();
			return $asignacionesFecha;
		}catch (QueryException $e){
			return ["error","Error al obtener la lista de fechas".$e->getMessage()];
		}
	}
}