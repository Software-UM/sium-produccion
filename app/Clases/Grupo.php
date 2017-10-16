<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 29/06/16
 * Time: 12:43 PM
 */

namespace app\Clases;

use App\Grupos;

/**
 * Class Grupo
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Grupo {
	private $id;
	private $grado;
	private $grupo;
	private $idModalidad;

	/**
	 * Grupo constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->grado = 0;
		$this->grupo = "";
		$this->idModalidad = 0;
	}

	/**
	 * @param $id
	 * @param $grupo
	 * @param $grado
	 * @param $idModalidad
	 * @return Grupo
	 */
	public static function withData($id, $grupo, $grado, $idModalidad) {
		$instance = new self();
		$instance->id = $id;
		$instance->grado = $grado;
		$instance->grupo = $grupo;
		$instance->idModalidad = $idModalidad;
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
	public function getGrado() {
		return $this->grado;
	}

	/**
	 * @param int $grado
	 */
	public function setGrado($grado) {
		$this->grado = $grado;
	}

	/**
	 * @return string
	 */
	public function getGrupo() {
		return $this->grupo;
	}

	/**
	 * @param string $grupo
	 */
	public function setGrupo($grupo) {
		$this->grupo = $grupo;
	}

	/**
	 * @return int
	 */
	public function getIdModalidad() {
		return $this->idModalidad;
	}

	/**
	 * @param int $idModalidad
	 */
	public function setIdModalidad($idModalidad) {
		$this->idModalidad = $idModalidad;
	}

	/**
	 * Obtiene la lista de grupo
	 * @return array
	 */
	public function getGrupos() {
		try {
			$grupos = Grupos::join('modalidad', 'modalidad.id', '=', 'grupos.id_modalidad')
				->select('grupos.*', 'modalidad.descripcion')->get();
			return $grupos;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de grupos: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene un grupo en específico
	 * @param $idGrupo
	 * @return array
	 */
	public function getGrupoOb($idGrupo){
		try {
			$grupos = Grupos::find($idGrupo);
			$this->setId($grupos->id);
			$this->setGrado($grupos->grado);
			$this->setGrupo($grupos->grupo);
			$this->setIdModalidad($grupos->id_modalidad);
			return $grupos;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de grupos: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de grupos por modalidad
	 * @param $idMod
	 * @return array
	 */
	public function getGruposMod($idMod){
		try {
			$grupos = Grupos::where('id_modalidad',$idMod)->get();
			return $grupos;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de grupos: ' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de grupos por modalidad
	 * @param $id
	 * @return array
	 */
	public function getGrupoM($id) {
		try {
			$grupos = Grupos::join('modalidad', 'modalidad.id', '=', 'grupos.id_modalidad')
				->where('grupos.id',$id)
				->select('grupos.*', 'modalidad.descripcion')->get();
			foreach ($grupos as $grupo){
				$this->setId($grupo->id);
				$this->setGrado($grupo->grado);
				$this->setGrupo($grupo->grupo);
				$this->setIdModalidad($grupo->id_modalidad);
			}
			return $grupos;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de grupos: ' . $e->getMessage()];
		}
	}

	/**
	 * Agrega un nuevo grupo
	 * recibe un obketo de tipo grupo
	 * @param Grupo $grupo
	 * @return array
	 */
	public function insertGrupo(Grupo $grupo) {
		try {
			$grupos = new Grupos();
			$grupos->grado = $grupo->getGrado();
			$grupos->grupo = $grupo->getGrupo();
			$grupos->id_modalidad = $grupo->getIdModalidad();
			$grupos->save();
			return ['success' => 'Se guardo correctamente el grupo'];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al guardar el grupo: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza el grupo
	 * @param Grupo $grupo
	 * @return array
	 */
	public function updateGrupo(Grupo $grupo) {
		try {
			$grupos = Grupos::find($grupo->getId());
			$grupos->grado = $grupo->getGrado();
			$grupos->grupo = $grupo->getGrupo();
			$grupos->id_modalidad = $grupo->getIdModalidad();
			$grupos->save();
			return ['success' => 'Se modifico correctamente el grupo'];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al modificar el grupo: ' . $e->getMessage()];
		}
	}

	/**
	 * Elimina un grupo
	 * @param $id
	 * @return array
	 */
	public function deleteGrupo($id) {
		try {
			$grupos = Grupos::find($id);
			$grupos->delete();
			return ['success' => 'Se elimino correctamente el grupo'];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al eliminar el grupo: ' . $e->getMessage()];
		}
	}
}