<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 27/06/16
 * Time: 02:20 PM
 */

namespace app\Clases;

use App\DetalleBajas;

/**
 * Class DetalleBaja
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class DetalleBaja {
    private $id;
    private $descripcion;
    private $fechaBaja;
    private $finiquito;
    private $liquidacion;
    private $idTipoBaja;
    private $idEmpleado;

    /**
     * DetalleBaja constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = "";
        $this->fechaBaja = "";
        $this->finiquito = 0;
        $this->liquidacion = 0;
        $this->idTipoBaja = 0;
        $this->idEmpleado = 0;
    }

    /**
	 * Contructor con parametros
     * @param $id
     * @param $descripcion
     * @param $fechaBaja
     * @param $finiquito
     * @param $liquidacion
     * @param $idTipoBaja
     * @param $idEmpleado
     * @return DetalleBaja
     */
    public static function withData($id, $descripcion, $fechaBaja, $finiquito,
                                    $liquidacion, $idTipoBaja, $idEmpleado) {
        $instance = new self();
        $instance->id = $id;
        $instance->descripcion = $descripcion;
        $instance->fechaBaja = $fechaBaja;
        $instance->finiquito = $finiquito;
        $instance->liquidacion = $liquidacion;
        $instance->idTipoBaja = $idTipoBaja;
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
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getFechaBaja() {
        return $this->fechaBaja;
    }

    /**
     * @param string $fechaBaja
     */
    public function setFechaBaja($fechaBaja) {
        $this->fechaBaja = $fechaBaja;
    }

    /**
     * @return int
     */
    public function getFiniquito() {
        return $this->finiquito;
    }

    /**
     * @param int $finiquito
     */
    public function setFiniquito($finiquito) {
        $this->finiquito = $finiquito;
    }

    /**
     * @return int
     */
    public function getLiquidacion() {
        return $this->liquidacion;
    }

    /**
     * @param int $liquidacion
     */
    public function setLiquidacion($liquidacion) {
        $this->liquidacion = $liquidacion;
    }

    /**
     * @return int
     */
    public function getIdTipoBaja() {
        return $this->idTipoBaja;
    }

    /**
     * @param int $idTipoBaja
     */
    public function setIdTipoBaja($idTipoBaja) {
        $this->idTipoBaja = $idTipoBaja;
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

    public function getDetalleBajas(){
        try{
            $detalleBaja = DetalleBajas::all();
            return $detalleBaja;
        }catch (Exception $e){
            return ['error'=>'Error al obtener lista detalle bajas: '.$e->getMessage()];
        }
    }

    public function getDetalleBaja($id){
        try{
            $detalleBaja = DetalleBajas::where('id',$id)->get();
            return $detalleBaja;
        }catch (Exception $e){
            return ['error'=>'Error al obtener detalle baja'.$e->getMessage()];
        }
    }

    public function insertDetalleBaja(DetalleBaja $detalleBaja){
        try{
            $detalleBajas = new DetalleBajas();
            $detalleBajas->descripcion = $detalleBaja->getDescripcion();
            $detalleBajas->fecha_baja = $detalleBaja->getFechaBaja();
            $detalleBajas->finiquito = $detalleBaja->getFiniquito();
            $detalleBajas->liquidacion = $detalleBaja->getLiquidacion();
            $detalleBajas->id_tipo_bajas = $detalleBaja->getIdTipoBaja();
            $detalleBajas->id_empleado = $detalleBaja->getIdEmpleado();
            $detalleBajas->save();
            return ['success'=>'Se guardo el registro detalle baja'];
        }catch (Exception $e){
            return ['error'=>'Error al guardar detalle baja'.$e->getMessage()];
        }
    }

    public function updateDetalleBaja(DetalleBaja $detalleBaja){
        try{
            $detalleBajas = DetalleBajas::find($detalleBaja->getId());
            $detalleBajas->descripcion = $detalleBaja->getDescripcion();
            $detalleBajas->fecha_baja = $detalleBaja->getFechaBaja();
            $detalleBajas->finiquito = $detalleBaja->getFiniquito();
            $detalleBajas->liquidacion = $detalleBaja->getLiquidacion();
            $detalleBajas->id_tipo_bajas = $detalleBaja->getIdTipoBaja();
            $detalleBajas->id_empleado = $detalleBaja->getIdEmpleado();
            $detalleBajas->save();
            return ['success'=>'se modifico detalle baja'];
        }catch (Exception $e){
            return ['error'=>'Error al guardar detalle baja'.$e->getMessage()];
        }
    }

    public function deleteDetalleBaja($id){
        try{
            $detalleBajas = DetalleBajas::find($id);
            $detalleBajas->delete();
            return ['success'=>'se elimino detalle baja'];
        }catch (Exception $e){
            return ['error'=>'Error al eliminar detalle baja'.$e->getMessage()];
        }
    }
}