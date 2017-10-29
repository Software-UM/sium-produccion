<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 29/06/16
 * Time: 01:00 PM
 */

namespace app\Clases;

use App\Modalidad;

/**
 * Class Mod
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases
 */
class Mod {
    private $id;
    private $descripcion;

    /**
     * Mod constructor.
     */
    public function __construct() {
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListModalidad(){
        try{
            $descripcion = Modalidad::all();
            return $descripcion;
        }catch (Exception $e){
            return ['error'=>'Error al obtener la descripcion'];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function getModalidad($id){
        try{
            $descripcion = Modalidad::find($id);
            return $descripcion;
        }catch (Exception $e){
            return ['error'=>'Error al obtener la descripcion'];
        }
    }

}