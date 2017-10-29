<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 29/06/16
 * Time: 01:30 PM
 */

namespace app\Clases;

use App\Carreras;

/**
 * Class Carrera
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Carrera {
	private $id;
	private $nombre;
	private $rvoe;
	private $cctPlantel;
	private $idModalidad;

	/**
	 * Carrera constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->nombre = "";
		$this->rvoe = "";
		$this->cctPlantel = 0;
		$this->idModalidad = 0;
	}

	/**
	 * inicializa la clase con variables recibidas por el usuario
	 * @param $id
	 * @param $nombre
	 * @param $rvoe
	 * @param $cctPlantel
	 * @param $idModalidad
	 * @return Carrera
	 */
	public static function withData($id, $nombre, $rvoe, $cctPlantel, $idModalidad) {
		$instance = new self();
		$instance->id = $id;
		$instance->nombre = $nombre;
		$instance->rvoe = $rvoe;
		$instance->cctPlantel = $cctPlantel;
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
	 * @return string
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * @param string $nombre
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	/**
	 * @return string
	 */
	public function getRvoe() {
		return $this->rvoe;
	}

	/**
	 * @param string $rvoe
	 */
	public function setRvoe($rvoe) {
		$this->rvoe = $rvoe;
	}

	/**
	 * @return int
	 */
	public function getCctPlantel() {
		return $this->cctPlantel;
	}

	/**
	 * @param int $cctPlantel
	 */
	public function setCctPlantel($cctPlantel) {
		$this->cctPlantel = $cctPlantel;
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
	 * obtiene la lista de carreras
	 * @return Carreras
	 */
	public function getCarreras() {
		try {
			$carreras = Carreras::join('plantel', 'cct', '=', 'cct_plantel')
				->join('modalidad', 'modalidad.id', '=', 'id_modalidad')
				->select('carreras.*', 'plantel.nombre AS plantelnom', 'modalidad.descripcion AS nommod')
				->get();
			return $carreras;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de carreras' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene la lista de carreras por campus
	 * @param $cct
	 * @return Carreras
	 */
	public function getCarreraCampus($cct) {
		try {
			$carreras = Carreras::where('cct_plantel', $cct)->get();
			return $carreras;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de carreras' . $e->getMessage()];
		}
	}

	/**
	 * retorna la lista de carreras por modalidad y campus
	 * @param $cct
	 * @param $mod
	 * @return Carreras
	 */
	public function getCarreraCampusModalidad($cct, $mod) {
		try {
			$carreras = Carreras::where([['cct_plantel', $cct],
				['id_modalidad', $mod]])->get();
			return $carreras;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de carreras' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene una carrera por su identificador
	 * @param $id
	 * @return Carreras;
	 */
	public function getCarrera($id) {
		try {
			$carreras = Carreras::find($id);
			$this->setId($id);
			$this->setNombre($carreras->nombre);
			$this->setRvoe($carreras->rvoe);
			$this->setIdModalidad($carreras->id_modalidad);
			$this->setCctPlantel($carreras->cct_plantel);
			return $carreras;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al obtener la lista de carreras' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene las carreras por modalidad
	 * @param $idMod
	 * @param $campus
	 * @return array
	 */
	public function getCarrerasMod($idMod,$campus){
		try{
			$carreras = Carreras::where([['cct_plantel',$campus],['id_modalidad',$idMod]])->get();
			foreach ($carreras as $carrera){
				$this->setId($carrera->id);
				$this->setNombre($carrera->nombre);
				$this->setRvoe($carrera->rvoe);
				$this->setIdModalidad($carrera->id_modalidad);
				$this->setCctPlantel($carrera->cct_plantel);
			}
			return $carreras;
		}catch (\Illuminate\Database\QueryException $e){
			return ['error' => 'Error al obtener la lista de carreras' . $e->getMessage()];
		}

	}

	/**
	 * Inserta una nueva carrera
	 * @param Carrera $carrera
	 * @return array
	 */
	public function insertCarrera(Carrera $carrera) {
		try {
			$carreras = new Carreras();
			$carreras->nombre = $carrera->getNombre();
			$carreras->rvoe = $carrera->getRvoe();
			$carreras->cct_plantel = $carrera->getCctPlantel();
			$carreras->id_modalidad = $carrera->getIdModalidad();
			$carreras->save();
			return ['success' => 'Se guardo la carrera: '.$carrera->getNombre()];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al guardar la carrera' . $e->getMessage()];
		}
	}

	/**
	 * modificar una carrera
	 * @param Carrera $carrera
	 * @return array
	 */
	public function updateCarrera(Carrera $carrera) {
		try {
			$carreras = Carreras::find($carrera->getId());
			$carreras->nombre = $carrera->getNombre();
			$carreras->rvoe = $carrera->getRvoe();
			$carreras->cct_plantel = $carrera->getCctPlantel();
			$carreras->id_modalidad = $carrera->getIdModalidad();
			$carreras->save();
			return ['success' => 'Se modifico la carrera: '.$carrera->getNombre()];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al modificar la carrera: ' . $e->getMessage()];
		}
	}

	/**
	 * Elimina una carrera por id
	 * @param $id
	 * @return array
	 */
	public function deleteCarrera($id) {
		try {
			$carreras = Carreras::find($id);
			$carreras->delete();
			return ['success' => 'Se elimino la carrera'];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Error al guardar la carrera' . $e->getMessage()];
		}
	}
}