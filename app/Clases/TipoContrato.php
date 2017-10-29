<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 27/06/16
 * Time: 12:39 PM
 */

namespace app\Clases;

use App\TipoContratos;

class TipoContrato {
    private $id;
    private $descripcion;

    /**
     * TipoContrato constructor.
     * @param $id
     * @param $descripcion
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = 0;
    }

    /**
     * @param $id
     * @param $descripcion
     * @return TipoContrato
     */
    public static function withData($id, $descripcion) {
        $instance = new self();
        $instance->id = $id;
        $instance->descripcion = $descripcion;
        return $instance;
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
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * @return TipoContratos
     */
    public function getTiposContrato() {
        try {
            $tipos = TipoContratos::all();
            return $tipos;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener tipos de contrato:' . $e->getMessage()];
        }
    }

    /**
     * @param $id
     * @return TipoContratos
     */
    public function getTipoContrato($id) {
        try {
            $tipos = TipoContratos::where('id', $id)->get();
            return $tipos;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener el tipo de contrato' . $e->getMessage()];
        }
    }

    /**
     * @param TipoContrato $tipoContrato
     * @return array
     */
    public function insertTipoContrato(TipoContrato $tipoContrato){
        try{
            $tipocontratos = new TipoContratos();
            $tipocontratos->descripcion = $tipoContrato->getDescripcion();
            $tipocontratos->save();
            return ['success','Se guardo el tipo de contrato'];
        }catch (Exception $e){
            return ['error' => 'Error al insertar el tipo de contrato: '.$e->getMessage()];
        }
    }

    /**
     * @param TipoContrato $tipoContrato
     * @return array
     */
    public function updateTipoContrato(TipoContrato $tipoContrato){
        try{
            $tipocontratos = TipoContratos::find($tipoContrato->getId());;
            $tipocontratos->descripcion = $tipoContrato->getDescripcion();
            $tipocontratos->save();
            return ['success','Se modifico el tipo contrato'];
        }catch (Exception $e){
            return ['error' => 'Error al modificar el tipo de contrato: '.$e->getMessage()];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteTipoContrato($id){
        try{
            $tipoContratos = TipoContratos::find($id);
            $tipoContratos->delete();
            return ['success','Se elimino el tipo contrato'];
        }catch (Exception $e){
            return ['error' => 'Error al eliminar el tipo de contrato: '.$e->getMessage()];
        }
    }
}