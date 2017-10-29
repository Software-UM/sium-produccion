<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 26/06/16
 * Time: 08:32 AM
 */

namespace app\Clases;

use App\Plantel;
use PhpParser\Node\Expr\Array_;

/**
 * Class Planteles
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Planteles {
    private $cct;
    private $nombre;
    private $idDireccion;
    private $clave;

    /**
     * Planteles constructor.
     */
    public function __construct() {
        $this->cct = "";
        $this->nombre = "";
        $this->idDireccion = 0;
        $this->clave = "";
    }


	/**
	 * Planteles constructor con datos
	 * @param $cct
	 * @param $nombre
	 * @param $idDireccion
	 * @param $clave
	 * @return Planteles
	 */
    public static function withData($cct, $nombre, $idDireccion, $clave) {
        $instance = new self();
        $instance->cct = $cct;
        $instance->nombre = $nombre;
        $instance->idDireccion = $idDireccion;
        $instance->clave = $clave;
        return $instance;
    }

    /**
     * @return string
     */
    public function getClave() {
        return $this->clave;
    }

    /**
     * @param string $clave
     */
    public function setClave($clave) {
        $this->clave = $clave;
    }

    /**
     * @return int
     */
    public function getCct() {
        return $this->cct;
    }

    /**
     * @param int $cct
     */
    public function setCct($cct) {
        $this->cct = $cct;
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
     * @return int
     */
    public function getIdDireccion() {
        return $this->idDireccion;
    }

    /**
     * @param int $idDireccion
     */
    public function setIdDireccion($idDireccion) {
        $this->idDireccion = $idDireccion;
    }

    public function getPlanteles() {
        try {
            $plantel = Plantel::join('direccion', 'direccion.id', '=', 'id_direccion')
                ->select('cct', 'nombre', 'clave', 'direccion.*')->get();
            return $plantel;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Ocurrio un error al listar las direcciones: ' . $e->getMessage() . " Línea" . $e->getLine()];
        }
    }

    public function getPlantel($cct) {
        try {
            $plantel = Plantel::join('direccion', 'direccion.id', '=', 'id_direccion')
                ->select('cct', 'nombre', 'clave', 'direccion.*')->where('cct', $cct)->get();
            foreach ($plantel as $item) {
                $this->setCct($item->cct);
                $this->setNombre($item->nombre);
                $this->setIdDireccion($item->id);
                $this->setClave($item->clave);
            }
            return $plantel;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el plantel:' . $e->getMessage() . " Línea" . $e->getLine()];
        }
    }

    public function insertPlantel(Planteles $planteles) {
        try {
            $plantel = new Plantel();
            $plantel->nombre = $planteles->getNombre();
            $plantel->id_direccion = $planteles->getIdDireccion();
            $plantel->clave = $planteles->getClave();
            $plantel->save();

            return ['success' => 'se guardo el plantel: '.$planteles->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al guardar el plantel:' . $e->getMessage() . " Línea" . $e->getLine()];
        }
    }

    public function updatePlantel(Planteles $planteles) {
        try {
            $plantel = Plantel::find($planteles->getCct());
            $plantel->nombre = $planteles->getNombre();
            $plantel->id_direccion = $planteles->getIdDireccion();
            $plantel->clave = $planteles->getClave();
            $plantel->save();
            return ['success' => 'se actualizó el plantel: '.$planteles->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'error al actualizar el plantel: ' . $e->getMessage() . " Línea" . $e->getLine()];
        }
    }

    public function deletePlantel($id) {
        try {
            $plantel = Plantel::find($id);
            $plantel->delete();
            return ['success' => 'Se elimino el plantel'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al eliminar el plantel:' . $e->getMessage() . " Línea" . $e->getLine()];
        }
    }


}
