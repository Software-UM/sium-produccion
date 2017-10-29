<?php
/**
 * Created by PhpStorm.
 * User: OSORIO
 * Date: 31/08/2016
 * Time: 07:41 AM
 */

namespace app\Clases;

use App\Incidencia;
use App\Clases\Asistencias;
use Illuminate\Database\QueryException;

/**
 * Class Incidencias
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Incidencias {
	private $id;
	private $tipoIncidencia;
	private $motivo;
	private $idAsistencia;
	private $createdAt;
	private $updatedAt;

	/**
	 * Incidencias constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->tipoIncidencia = 0;
		$this->motivo = "";
		$this->idAsistencia = 0;
		$this->createdAt = date('Y-m-d');
		$this->updatedAt = date('Y-m-d');
	}

	/**
	 * Incidencias contructor with data
	 * @param $id
	 * @param $tipoIncidencia
	 * @param $motivo
	 * @param $idAsistencia
	 * @param $createdAt
	 * @param $updatedAt
	 * @return Incidencias
	 */
	public function withData($id,$tipoIncidencia,$motivo,$idAsistencia,$createdAt,$updatedAt){
		$instance = new self();
		$instance->id = $id;
		$instance->tipoIncidencia = $tipoIncidencia;
		$instance->motivo = $motivo;
		$instance->idAsistencia = $idAsistencia;
		$instance->createdAt = $createdAt;
		$instance->updatedAt = $updatedAt;
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
	public function getTipoIncidencia() {
		return $this->tipoIncidencia;
	}

	/**
	 * @param int $tipoIncidencia
	 */
	public function setTipoIncidencia($tipoIncidencia) {
		$this->tipoIncidencia = $tipoIncidencia;
	}

	/**
	 * @return string
	 */
	public function getMotivo() {
		return $this->motivo;
	}

	/**
	 * @param string $motivo
	 */
	public function setMotivo($motivo) {
		$this->motivo = $motivo;
	}

	/**
	 * @return int
	 */
	public function getIdAsistencia() {
		return $this->idAsistencia;
	}

	/**
	 * @param int $idAsistencia
	 */
	public function setIdAsistencia($idAsistencia) {
		$this->idAsistencia = $idAsistencia;
	}

	/**
	 * @return false|string
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param false|string $createdAt
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @return false|string
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * @param false|string $updatedAt
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
	}

	/**
	 * Inserta una nueva incidencia de horario para el personal
	 * @param Incidencias $incidencia
	 * @return array
	 */
	public static function insertEntrada(Incidencias $incidencia){
		try {
			$incidencias = new Incidencia();
			$incidencias->tipo_incidencia = $incidencia->getTipoIncidencia();
			$incidencias->motivo = $incidencia->getMotivo();
			$incidencias->id_asistencia = $incidencia->getIdAsistencia();
			$incidencias->save();
			return ['success'=>'Se guardó la incidencia con éxito'];
		} catch (QueryException $e) {
			return ['error' => 'error al regitrar incidencia: Hora Entrada' . $e->getMessage()];
		}
	}
}