<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 29/06/16
 * Time: 09:01 AM
 */

namespace app\Clases;

use App\Materias;
use Illuminate\Database\QueryException;

/**
 * Class Materia
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Materia {
    private $clave;
    private $nombre;

    /**
     * Materia constructor.
     */
    public function __construct() {
        $this->clave = "";
        $this->nombre = "";
    }

    /**
	 * Constructor con datos
     * @param $clave
     * @param $nombre
     * @return Materia
     */
    public static function withData($clave, $nombre) {
        $instance = new self();
        $instance->clave = $clave;
        $instance->nombre = $nombre;
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
	 * Get lista materias
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getMaterias() {
        try {
            $materia = Materias::all();
            return $materia;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al obtener la lista de materias: ' . $e->getMessage()];
        }
    }

	/**
	 * Obtiene un objeto materia
	 * @param $id
	 * @return array
	 */
    public function getMateria($id) {
        try {
            $materia = Materias::find($id);
            $this->setClave($materia->clave);
            $this->setNombre($materia->nombre);
            return $materia;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al obtener la lista de materias: ' . $e->getMessage()];
        }
    }

	/**
	 * Ingresa nueva materia
	 * @param Materia $materia
	 * @return array
	 */
    public function insertMateria(Materia $materia) {
        try {
            $materias = new Materias();
            $materias->nombre = $materia->getNombre();
            $materias->clave = $materia->getClave();
            $materias->save();
            return ['success' => 'Se guardo la materia: ' . $materia->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al guardar la materia: ' . $e->getMessage()];
        }
    }

	/**
	 * Actualiza la materia
	 * @param Materia $materia
	 * @param $old
	 * @return array
	 */
    public function updateMateria(Materia $materia,$old) {
        try {
            $materias = Materias::find($old);
            $materias->nombre = $materia->getNombre();
            $materias->clave = $materia->getClave();
            $materias->save();
            return ['success' => 'Se modifico la materia: ' . $materia->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al modificar la materia: ' . $e->getMessage()];
        }
    }

	/**
	 * Elimina la materia
	 * @param $id
	 * @return array
	 */
    public function deleteMateria($id) {
        try {
            $materias = Materias::find($id);
            $materias->delete();
            return ['success' => 'Se elimino la materia'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Error al eliminar la materia' . $e->getMessage()];
        }
    }

    public function getMateriaPlan($grado,$carrera,$modalidad){
		try{
			$materias = Materias::join("materias_plan","id_materias","=","materias.clave")
				->join("asignacion_plan","asignacion_plan.id","=","id_asignacion_plan")
				->select("materias.*")
				->where([["asignacion_plan.cuatrimestre",$grado],
					["asignacion_plan.id_carreras",$carrera],
					["asignacion_plan.id_modalidad",$modalidad]])
				->get();
			return $materias;
		}catch (QueryException $e){
			return ["error"=>"Falló al obtener las materias por plan de estudios"];
		}
	}
}