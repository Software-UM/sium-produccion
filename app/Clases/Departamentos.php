<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 25/06/16
 * Time: 08:13 AM
 */

namespace App\Clases;


use App\Departamento;
use Mockery\CountValidator\Exception;

/**
 * Class Departamentos
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package App\Clases
 */
class Departamentos {
    private $id;
    private $nombre;
    private $descripcion;

    /**
     * Departamentos constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->nombre = "";
        $this->descripcion = "";
    }

    /**
	 * inicializa un contructor con datos
     * @param $id
     * @param $nombre
     * @param $descripcion
     * @return Departamentos
     */
    public static function withData($id, $nombre, $descripcion) {
        $instance = new self();
        $instance->id = $id;
        $instance->nombre = $nombre;
        $instance->descripcion = $descripcion;
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
	 * obtiene la lista de los departamentos
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDepartamentos() {
        try {
            $departamento = Departamento::all();
            return $departamento;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el departamento:' . $e->getMessage()];
        }
    }

	/**
	 * obtiene la lista de departamento sin docentes
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function getDepartamentosWithoutDocentes(){
		try {
			$departamento = Departamento::where("nombre","<>","DOCENTE")->get();
			return $departamento;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener el departamento:' . $e->getMessage()];
		}
	}

    /**
	 * crea un nuevo departamento
     * @param Departamentos $departamento
     * @return array
     */
    public function insertDepartamento(Departamentos $departamento) {
        try {
            $modDep = new Departamento();
            $modDep->nombre = $departamento->getNombre();
            $modDep->descripcion = $departamento->getDescripcion();
            $modDep->save();
            return ['success' => 'Se agregó departamento: '.$departamento->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al agregar departamento: ' . $e->getMessage()];
        }
    }

    /**
	 * elimina
     * @param $idDepa
     * @return array
     */
    public function deleteDepartamento($idDepa) {
        try {
            $modDep = Departamento::find($idDepa);
            $modDep->delete();
            return ['success' => 'Se elimino departamento'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al eliminar departamento: ' . $e->getMessage()];
        }
    }

    /**
	 * midifica
     * @param Departamentos $departamento
     * @return array
     */
    public function updateDepartamento(Departamentos $departamento) {
        try {
            $modDep = Departamento::find($departamento->getId());
            $modDep->nombre = $departamento->getNombre();
            $modDep->descripcion = $departamento->getDescripcion();
            $modDep->save();
            return ['success' => 'Se actualizó el Departamento: '.$departamento->getNombre()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al actualizar Departamento: ' . $e->getMessage()];
        }
    }

    /**
	 * obtiene un unico departamento
     * @param $id
     * @return array
     */
    public function getDepartamento($id) {
        try {
            $departamento = Departamento::where('id', $id)->get();
            foreach ($departamento as $item) {
                $this->setId($item->id);
                $this->setNombre($item->nombre);
                $this->setDescripcion($item->descripcion);
            }
            return $departamento;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el departamento:' . $e->getMessage()];
        }
    }


}