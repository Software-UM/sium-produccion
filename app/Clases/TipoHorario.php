<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 28/06/16
 * Time: 11:01 AM
 */

namespace app\Clases;

use App\TipoHorarios;

class TipoHorario {
    private $id;
    private $descripcion;
    private $idParametro;

    /**
     * TipoHorario constructor.
     * @param $id
     * @param $descripcion
     * @param $idParametro
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = "";
        $this->idParametro = 0;
    }

    /**
     * @param $id
     * @param $descripcion
     * @param $idParametro
     * @return TipoHorario
     */
    public static function withData($id, $descripcion, $idParametro) {
        $instance = new self();
        $instance->id = $id;
        $instance->descripcion = $descripcion;
        $instance->idParametro = $idParametro;
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
     * @return int
     */
    public function getIdParametro() {
        return $this->idParametro;
    }

    /**
     * @param int $idParametro
     */
    public function setIdParametro($idParametro) {
        $this->idParametro = $idParametro;
    }

    public function getTipoHorarios() {
        try {
            $tipoHorarios = TipoHorarios::all();
            return $tipoHorarios;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener lista de horarios'];
        }
    }

    public function getTipoHorario($id) {
        try {
            $tipoHorarios = TipoHorarios::find($id);
            return $tipoHorarios;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener el horario'];
        }
    }

    public function insertTipoHorario(TipoHorario $tipoHorario) {
        try {
            $tipoHorarios = new TipoHorarios();
            $tipoHorarios->descripcion = $tipoHorario->getDescripcion();
            $tipoHorarios->id_parametros = $tipoHorario->getIdParametro();
            $tipoHorarios->save();
            return ['success'=>'Se guardo el registro horario'];
        } catch (Exception $e) {
            return ['error'=>'Error al guardar el registro horario'];
        }
    }

    public function updateTipoHorario(TipoHorario $tipoHorario){
        try {
            $tipoHorarios = new TipoHorarios();
            $tipoHorarios->descripcion = $tipoHorario->getDescripcion();
            $tipoHorarios->id_parametros = $tipoHorario->getIdParametro();
            $tipoHorarios->save();
            return ['success'=>'Se modifico el registro horario'];
        } catch (Exception $e) {
            return ['error'=>'Error al modifico el registro horario'.$e->getMessage()];
        }
    }

    public function deleteTipoHorario($id){
        try {
            $tipoHorarios = TipoHorarios::find($id);
            $tipoHorarios->delete();
            return ['success'=>'Se elimino el registro horario'];
        } catch (Exception $e) {
            return ['error'=>'Error al elimino el registro horario'.$e->getMessage()];
        }
    }
}