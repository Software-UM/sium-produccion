<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 28/06/16
 * Time: 11:34 AM
 */

namespace app\Clases;

use App\Parametros;

/**
 * Class Parametro
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Parametro {
    private $id;
    private $tiempoAntes;
    private $tiempoDespues;

    /**
     * Parametro constructor.
     */
    public function __construct() {
        $this->id = 0;
        $this->tiempoAntes = 0;
        $this->tiempoDespues = 0;
    }

	/**
	 * Constructor con datos
	 * @param $id
	 * @param $tiempoAntes
	 * @param $tiempoDespues
	 * @return Parametro
	 */
    public static function withData($id, $tiempoAntes, $tiempoDespues) {
        $instance = new self();
        $instance->id = $id;
        $instance->tiempoAntes = $tiempoAntes;
        $instance->tiempoDespues = $tiempoDespues;
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
     * @return int
     */
    public function getTiempoAntes() {
        return $this->tiempoAntes;
    }

    /**
     * @param int $tiempoAntes
     */
    public function setTiempoAntes($tiempoAntes) {
        $this->tiempoAntes = $tiempoAntes;
    }

    /**
     * @return int
     */
    public function getTiempoDespues() {
        return $this->tiempoDespues;
    }

    /**
     * @param int $tiempoDespues
     */
    public function setTiempoDespues($tiempoDespues) {
        $this->tiempoDespues = $tiempoDespues;
    }

	/**
	 * Obtiene la lista de parametros
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
    public function getParametros() {
        try {
            $parametros = Parametros::all();
            return $parametros;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener los parametros'];
        }
    }

	/**
	 * Obtiene el parametro
	 * @param $id
	 * @return array
	 */
    public function getParametro($id) {
        try {
            $parametros = Parametros::find($id);
            return $parametros;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener los parametros'];
        }
    }

	/**
	 * Registra nuevo parametro
	 * @param Parametro $parametro
	 * @return array
	 */
    public function insertParametro(Parametro $parametro) {
        try {
            $parametros = new Parametros();
            $parametros->tiempo_antes = $parametro->getTiempoAntes();
            $parametros->tiempo_despues = $parametro->getTiempoDespues();
            $parametros->save();
            return ['success' => 'Parametros guardados'];
        } catch (Exception $e) {
            return ['success' => 'Error al guardar los parametros de configuracion: ' . $e->getMessage()];
        }
    }

	/**
	 * Actualiza el parametro
	 * @param Parametro $parametro
	 * @return array
	 */
    public function updateParametro(Parametro $parametro) {
        try {
            $parametros = Parametros::find($parametro->getId());
            $parametros->tiempo_antes = $parametro->getTiempoAntes();
            $parametros->tiempo_despues = $parametro->getTiempoDespues();
            $parametros->save();
            return ['success' => 'Parametros actualizados'];
        } catch (Exception $e) {
            return ['success' => 'Error al actualizar los parametros de configuracion: ' . $e->getMessage()];
        }
    }

	/**
	 * Elimina el parametro
	 * @param $id
	 * @return array
	 */
    public function deleteParametro($id){
        try {
            $parametros = Parametros::find($id);
            $parametros->delete();
            return ['success' => 'Parametro eliminado'];
        } catch (Exception $e) {
            return ['success' => 'Error al elminar los parametros de configuracion: ' . $e->getMessage()];
        }
    }
}