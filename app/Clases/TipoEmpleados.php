<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 25/06/16
 * Time: 08:43 AM
 */

namespace app\Clases;

use App\TipoEmpleado;
use Illuminate\Foundation\Console\IlluminateCaster;
use Mockery\CountValidator\Exception;

class TipoEmpleados {
    private $id;
    private $descripcion;

    /**
     * TipoEmpleados constructor withData.
     * @param $id
     * @param $descripcion
     * @return TipoEmpleados
     */
    public static function withData($id, $descripcion) {
        $intance = new self();
        $intance->id = $id;
        $intance->descripcion = $descripcion;
        return $intance;
    }

    /**
     * TipoEmpleados constructor.
     * @param $id
     * @param $descripcion
     */
    public function __construct() {
        $this->id = 0;
        $this->descripcion = "";
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
     * @return array
     */
    public function getTipoEmpleado($id) {
        try {
            $tipo = TipoEmpleado::where('id', $id)->get();
            foreach ($tipo as $item) {
                $this->setId($item->id);
                $this->setDescripcion($item->descripcion);
            }
            return $tipo;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener el tipo Empleado:' . $e->getMessage()];
        }
    }

    public function getTipos(){
        try{
            $tipo = TipoEmpleado::all();
            return $tipo;
        }catch (\Illuminate\Database\QueryException $e){
            return ['error' => 'Falló al obtener el tipo Empleado:' . $e->getMessage()];
        }
    }

    /**
     * @param TipoEmpleados $empleados
     * @return array
     */
    public function insertTipoEmpleado(TipoEmpleados $empleados) {
        try {
            $modEmpl = new TipoEmpleado();
            $modEmpl->descripcion = $empleados->getDescripcion();
            $modEmpl->save();
            return ['success' => 'se guardo el tipo Empleado: '.$empleados->getDescripcion()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al guardar el tipo Empleado:' . $e->getMessage()];
        }
    }

    /**
     * @param TipoEmpleados $empleados
     * @return array
     */
    public function updateTipoEmpleado(TipoEmpleados $empleados) {
        try {
            $modEmpl = TipoEmpleado::find($empleados->getId());
            $modEmpl->id = $empleados->getId();
            $modEmpl->descripcion = $empleados->getDescripcion();
            $modEmpl->save();
            return ['success' => 'se actualizo el tipo Empleado: '.$empleados->getDescripcion()];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al modificar el tipo Empleado:' . $e->getMessage()];
        }
    }

    /**
     * @param $idTipo
     * @return array
     */
    public function deleteTipoEmpleado($idTipo) {
        try {
            $modTipo = TipoEmpleado::find($idTipo);
            $modTipo->delete();
            return ['success' => 'Se elimino el Tipo usuario'];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al eliminar el tipo Empleado:' . $e->getMessage()];
        }
    }

    /**
     * @return TipoEmpleado
     */
    public function listTipoEmpleado() {
        try {
            $tipoEmpleado = TipoEmpleado::all();
            return $tipoEmpleado;
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => 'Falló al obtener los tipo empleado:' . $e->getMessage()];
        }
    }
}