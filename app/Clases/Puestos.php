<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 25/06/16
 * Time: 02:32 PM
 */

namespace app\Clases;


use App\Puesto;

class Puestos {
    private $id;
    private $descripcion;

    /**
     * Puestos constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = "";
    }

    /**
     * @param $id
     * @param $descripcion
     * @return Puestos
     */
    public static function withData($id, $descripcion) {
        $instance = new self();
        $instance->id = $id;
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
     * @return Puesto
     */
    public function getPuestos() {
        try {
            $puestos = Puesto::all();
            return $puestos;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el puesto:' . $e->getMessage()];
        }
    }

    /**
     * @return Puesto fuction
     */
    public function getPuesto($id) {
        try {
            $puesto = Puesto::where('id', $id)->get();
            foreach ($puesto as $item) {
                $this->setId($item->id);
                $this->setDescripcion($item->descripcion);
            }
            return $puesto;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el puesto:' . $e->getMessage()];
        }
    }

    /**
     * @param Puestos $puestos
     * @return array
     */
    public function insertPuesto(Puestos $puestos) {
        try {
            $puesto = new Puesto();
            $puesto->descripcion = $puestos->getDescripcion();
            $puesto->save();
            return ['success' => 'Se agrego el nuevo puesto'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al ingresar el puesto:' . $e->getMessage()];
        }
    }

    /**
     * @param Puestos $puestos
     * @return array
     */
    public function updatePuesto(Puestos $puestos) {
        try {
            $puesto = Puesto::find($puestos->getId());
            $puesto->descripcion = $puestos->getDescripcion();
            $puesto->save();
            return ['success' => 'Se modifico el puesto: '.$puestos->getDescripcion()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al modificar el puesto:' . $e->getMessage()];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id) {
        try {
            $puesto = Puesto::find($id);
            $puesto->delete();
            return ['success' => 'Se elimino el puesto'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al eliminar el puesto:' . $e->getMessage()];
        }
    }
}