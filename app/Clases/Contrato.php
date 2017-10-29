<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 27/06/16
 * Time: 09:54 AM
 */

namespace app\Clases;

use App\Contratos;

/**
 * Class Contrato
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Contrato {
    private $id;
    private $fechaContratacion;
    private $fechaVencimiento;
    private $idContrato;
    private $idEmpleado;

    /**
     * Contrato constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->fechaContratacion = "";
        $this->fechaVencimiento = "";
        $this->idContrato = 0;
        $this->idEmpleado = 0;
    }

	/**
	 * inicializa el contructor con datos
	 * @param $id
	 * @param $fechaContratacion
	 * @param $fechaVencimiento
	 * @param $idContrato
	 * @param $idEmpleado
	 * @return Contrato
	 */
    public static function withData($id, $fechaContratacion, $fechaVencimiento, $idContrato, $idEmpleado) {
        $instance = new self();
        $instance->id = $id;
        $instance->fechaContratacion = $fechaContratacion;
        $instance->fechaVencimiento = $fechaVencimiento;
        $instance->idContrato = $idContrato;
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
    public function getFechaContratacion() {
        return $this->fechaContratacion;
    }

    /**
     * @param string $fechaContratacion
     */
    public function setFechaContratacion($fechaContratacion) {
        $this->fechaContratacion = $fechaContratacion;
    }

    /**
     * @return string
     */
    public function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

    /**
     * @param string $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento) {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    /**
     * @return int
     */
    public function getIdContrato() {
        return $this->idContrato;
    }

    /**
     * @param int $idContrato
     */
    public function setIdContrato($idContrato) {
        $this->idContrato = $idContrato;
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
     * @return Contratos|array
     */
    public function getContratos() {
        try {
            $contrato = Contratos::all();
            return $contrato;
        } catch (Exception $e) {
            return ['error' => 'Ocurrio un error al obtener los contratos: ' . $e->getMessage()];
        }
    }

    public function getContrato($id) {
        try {
            $contratos = Contratos::where('id', $id)->get();
            return $contratos;
        } catch (Exception $e) {
            return ['error' => 'Ocorrio un error al obtener el contrato: ' . $e->getMessage()];
        }
    }

    /**
     * @param Contrato $contrato
     * @return array
     */
    public function insertContrato(Contrato $contrato) {
        try {
            $contratos = new Contratos();
            $contratos->fecha_contratacion = $contrato->getFechaContratacion();
            $contratos->fecha_vencimiento = $contrato->getFechaVencimiento();
            $contratos->id_tipo_contrato = $contrato->getIdContrato();
            $contratos->id_empleado = $contrato->getIdEmpleado();
            $contratos->save();
            return ['success' => 'Se guardo correctamente el contrato'];
        } catch (Exception $e) {
            return ['error' => 'Error al guardar el contrato: ' . $e->getMessage()];
        }
    }

    public function updateContrato(Contrato $contrato){
        try{
            $contratos = Contratos::find($contrato->getId());
            $contratos->fecha_contratacion = $contrato->getFechaContratacion();
            $contratos->fecha_vencimiento = $contrato->getFechaVencimiento();
            $contratos->id_tipo_contrato = $contrato->getIdContrato();
            $contratos->id_empleado = $contrato->getIdEmpleado();
            $contratos->save();
            return ['success' => 'Se guardo correctamente el contrato'];
        }catch (Exception $e){
            return ['error' => 'Error al modificar el contrato: ' . $e->getMessage()];
        }
    }

    public function deleteContrato(Contrato $contrato){
        try{
            $contratos = Contratos::find($contrato->getId());
            $contratos->delete();
            return ['success' => 'Se elimino el registro'];
        }catch (Exception $e){
            return ['error' => 'Error al eliminar el contrato: ' . $e->getMessage()];
        }
    }
}