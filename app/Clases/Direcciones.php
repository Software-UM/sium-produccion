<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 25/06/16
 * Time: 12:42 PM
 */

namespace app\Clases;

use App\Direccion;
use Mockery\CountValidator\Exception;

/**
 * Class Direcciones
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Direcciones {
    private $id;
    private $calle;
    private $colonia;
    private $cp;
    private $municipio;
    private $estado;

    /**
     * Direcciones constructor.
     * @param $id
     * @param $calle
     * @param $colonia
     * @param $cp
     * @param $municipio
     * @param $estado
     */
    public function __construct() {
        $this->id = 0;
        $this->calle = "";
        $this->colonia = "";
        $this->cp = "";
        $this->municipio = 0;
        $this->estado = 0;
    }

    /**
     * Direcciones constructor with data.
     * @param $id
     * @param $calle
     * @param $colonia
     * @param $cp
     * @param $municipio
     * @param $estado
     * @return Direcciones
     */
    public static function withData($id, $calle, $colonia, $cp, $municipio, $estado) {
        $instance = new self();
        $instance->id = $id;
        $instance->calle = $calle;
        $instance->colonia = $colonia;
        $instance->cp = $cp;
        $instance->municipio = $municipio;
        $instance->estado = $estado;
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
    public function getCalle() {
        return $this->calle;
    }

    /**
     * @param string $calle
     */
    public function setCalle($calle) {
        $this->calle = $calle;
    }

    /**
     * @return string
     */
    public function getColonia() {
        return $this->colonia;
    }

    /**
     * @param string $colonia
     */
    public function setColonia($colonia) {
        $this->colonia = $colonia;
    }

    /**
     * @return string
     */
    public function getCp() {
        return $this->cp;
    }

    /**
     * @param string $cp
     */
    public function setCp($cp) {
        $this->cp = $cp;
    }

    /**
     * @return string
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     * @param string $municipio
     */
    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    /**
     * @return string
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDirecciones() {
        try {
            $direcciones = Direccion::all();
            return $direcciones;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener las direcciones: ' . $e->getMessage()];
        }
    }

    /**
     * @param $id
     * @return Direccion
     */
    public function getDireccion($id) {
        try {
            $direcciones = Direccion::where('id', $id)->get();
            foreach ($direcciones as $direccion){
                $this->setId($direccion->id);
                $this->setEstado($direccion->estado);
                $this->setCalle($direccion->calle);
                $this->setColonia($direccion->colonia);
                $this->setCp($direccion->cp);
                $this->setMunicipio($direccion->municipio);
            }
            return $direcciones;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener las direcciones: ' . $e->getMessage(). " Línea" . $e->getLine()];
        }
    }

    /**
     * @param Direcciones $direcciones
     * @return array
     */
    public function insertDireccion(Direcciones $direcciones) {
        try {
            $modDir = new Direccion();
            $modDir->calle = $direcciones->getCalle();
            $modDir->colonia = $direcciones->getColonia();
            $modDir->cp = $direcciones->getCp();
            $modDir->municipio = $direcciones->getMunicipio();
            $modDir->estado = $direcciones->getEstado();
            $modDir->save();
            $id = $modDir->id;
            return ['success' => 'Se agrego la nueva direccion','id'=>$id];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener las direcciones: ' . $e->getMessage(). " Línea" . $e->getLine()];
        }
    }

    /**
     * @param Direcciones $direcciones
     * @return array
     */
    public function updateDireccion(Direcciones $direcciones) {
        try {
        	$response = ['id'=>0];
        	if ($direcciones->getId()>0){
				$modDir = Direccion::find($direcciones->getId());
				$modDir->calle = $direcciones->getCalle();
				$modDir->colonia = $direcciones->getColonia();
				$modDir->cp = $direcciones->getCp();
				$modDir->estado = $direcciones->getEstado();
				$modDir->municipio = $direcciones->getMunicipio();
				$modDir->save();
				$response['id'] = $direcciones->getId();
			}else{
				$responses = $this->insertDireccion($direcciones);
				$response['id'] = $responses['id'];
			}
            return ['success' => 'Se actualizo la nueva direccion','id'=>$response['id']];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al actualizar Direccion: ' . $e->getMessage(). " Línea" . $e->getLine()];
        }
    }

    /**
     * @param Direcciones $direcciones
     * @return array
     */
    public function deleteDireccion(Direcciones $direcciones) {
        try {
            $modDir = Direccion::find($direcciones->getId());
            $modDir->delete();
            return ['success' => 'Direccion eliminada'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al eliminar Direccion: ' . $e->getMessage(). " Línea" . $e->getLine()];
        }
    }

}