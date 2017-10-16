<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 28/06/16
 * Time: 08:02 AM
 */

namespace app\Clases;

use App\TipoBaja;

class TipoBajas {
    private $id;
    private $motivo;

    /**
     * TipoBajas constructor.
     * @param $id
     * @param $motivo
     */
    public function __construct() {
        $this->id = 0;
        $this->motivo = "";
    }

    /**
     * @param $id
     * @param $motivo
     * @return TipoBajas
     */
    public static function withData($id, $motivo) {
        $instance = new self();
        $instance->id = $id;
        $instance->motivo = $motivo;
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
    public function getMotivo() {
        return $this->motivo;
    }

    /**
     * @param string $motivo
     */
    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }


    public function getTiposBajas() {
        try {
            $tipoBajas = TipoBaja::all();
            return $tipoBajas;
        } catch (Exception $e) {
            return ['error', 'Error al obtener lista de Tipos de bajas' . $e->getMessage()];
        }
    }

    public function getTipoBaja($id) {
        try {
            $tipoBajas = TipoBaja::find($id);
            return $tipoBajas;
        } catch (Exception $e) {
            return ['error', 'Error al obtener lista de Tipo de baja' . $e->getMessage()];
        }
    }

    public function insertTipoBaja(TipoBajas $bajas) {
        try {
            $tipoBaja = new TipoBaja();
            $tipoBaja->motivo = $bajas->getMotivo();
            $tipoBaja->save();
            return ['success', 'Se guardo el tipo bajas'];
        } catch (Exception $e) {
            return ['error' . 'Error al guardar tipo de bajas' . $e->getMessage()];
        }
    }

    public function updateTipoBajas(TipoBajas $bajas) {
        try {
            $tipoBaja = TipoBaja::find($bajas->getId());
            $tipoBaja->motivo = $bajas->getMotivo();
            $tipoBaja->save();
            return ['success', 'Se modifico el tipo bajas'];
        } catch (Exception $e) {
            return ['error' . 'Error al modificar tipo de bajas' . $e->getMessage()];
        }
    }

    public function deleteTipoBajas($id) {
        try {
            $tipoBaja = TipoBaja::find($id);
            $tipoBaja->delete();
            return ['success', 'Se elimino el tipo bajas'];
        } catch (Exception $e) {
            return ['error' . 'Error al elimino  tipo de bajas' . $e->getMessage()];
        }
    }
}