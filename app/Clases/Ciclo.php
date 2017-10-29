<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 30/06/16
 * Time: 08:32 AM
 */

namespace app\Clases;

use App\Ciclos;
use Log;

/**
 * Class Ciclo
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Ciclo {
    private $id;
    private $descripcion;
    private $nombre;
    private $nombreCorto;
    private $activo;

    /**
     * Ciclo constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = "";
        $this->nombre = "";
        $this->nombreCorto = "";
        $this->activo = 0;
    }

    /**
	 * inicializa un contructor con datos
     * @param $id
     * @param $descripcion
     * @param $nombre
     * @param $nombreCorto
     * @param $activo
     * @return Ciclo
     */
    public static function withData($id, $descripcion, $nombre, $nombreCorto, $activo) {
        $instance = new self();
        $instance->id = $id;
        $instance->descripcion = $descripcion;
        $instance->nombre = $nombre;
        $instance->nombreCorto = $nombreCorto;
        $instance->activo = $activo;
        return $instance;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * @param int $activo
     */
    public function setActivo($activo) {
        $this->activo = $activo;
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
    public function getNombreCorto() {
        return $this->nombreCorto;
    }

    /**
     * @param string $nombreCorto
     */
    public function setNombreCorto($nombreCorto) {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return Ciclos
     */
    public function getCiclos() {
        try {
            $ciclos = Ciclos::all();
            return $ciclos;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al obtener la lista de ciclos: ' . $e->getMessage()];
        }
    }

    /**
	 * obtiene el ciclo activo
     * @return array
     */
    public function getCicloActivo() {
        try {
            $ciclos = Ciclos::where('activo', 1)->get();
            return $ciclos;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al obtener el ciclo activo: ' . $e->getMessage()];
        }
    }

	/**
	 * obtiene un ciclo
	 * @param $id
	 * @return array
	 */
    public function getCiclo($id) {
        try {
            $ciclos = Ciclos::find($id);
            $this->setId($ciclos->id);
            $this->setNombre($ciclos->nombre);
            $this->setDescripcion($ciclos->descripcion);
            $this->setNombreCorto($ciclos->nombre_corto);
            $this->setActivo($ciclos->activo);
            return $ciclos;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al obtener el ciclo: ' . $e->getMessage()];
        }
    }

    /**
     * @param Ciclo $ciclo
     * @return array
     */
    public function insertCiclo(Ciclo $ciclo) {
        try {
            $ciclos = new Ciclos();
            $ciclos->descripcion = $ciclo->getDescripcion();
            $ciclos->nombre = $ciclo->getNombre();
            $ciclos->nombre_corto = $ciclo->getNombreCorto();
            $ciclos->activo = $ciclo->getActivo();
            $ciclos->save();
            return ['success' => 'El ciclo fue guardado: ' . $ciclo->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            //Log::error('Error al guardar el ciclo: '.$e->getMessage());
            return ['error' => 'Error al guardar el ciclo: ' . $e->getMessage()];
        }
    }

    /**
     * @param Ciclo $ciclo
     * @return array
     */
    public function updateCiclo(Ciclo $ciclo) {
        try {
            $ciclos = Ciclos::find($ciclo->getId());
            $ciclos->descripcion = $ciclo->getDescripcion();
            $ciclos->nombre = $ciclo->getNombre();
            $ciclos->nombre_corto = $ciclo->getNombreCorto();
            //$ciclos->activo = $ciclo->getActivo();
            $ciclos->save();
            return ['success' => 'El ciclo fue modificado: ' . $ciclo->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            //Log::error('Error al modificar el ciclo' . $e->getMessage());
            return ['error' => 'Error al modificar el ciclo: ' . $e->getMessage()];
        }
    }

    public function setCicloActivo($id){
        try{
            //quitamos la seleccion del ciclo actual
            Ciclos::where('activo',1)->update(['activo'=>0]);
            //Activamos el nuevo ciclo
            $ciclos = Ciclos::find($id);
            $ciclos->activo = 1;
            $ciclos->save();
            return ['success' => 'El ciclo fue activado: ' . $ciclos->nombre];
        }catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al activar ciclo el ciclo: ' . $e->getMessage()];
        }
    }
    /**
     * @param $id
     * @return array
     */
    public function deleteCiclo($id) {
        try {
            $ciclos = Ciclos::find($id);
            $ciclos->delete();
            return ['success' => 'Ciclo Elimnado'];
        } catch (\Illuminate\Database\QueryException $e) {
            //Log::error('Error al eliminar el ciclo' . $e->getMessage());
            return ['error' => 'Error al eliminar el ciclo: ' . $e->getMessage()];
        }
    }
}