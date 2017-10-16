<?php
/**
 * Created by PhpStorm.
 * User: OSORIO
 * Date: 30/08/2016
 * Time: 10:08 AM
 */

namespace app\Clases;


class Utilerias {

	/**
	 * Metodo para obtener el dia de la semana
	 * conforme a esta en la base de datos.
	 * Partiendo del 0 para lunes
	 * @param $fechaPHP
	 * @return int
	 */
	public static function getDiaDB($fechaPHP){
		//obtenemos el dias de la semana
		$weekday = date('N', strtotime($fechaPHP));
		//comparamos el dia conforme a esta en la bases de datos
		//Partiendo del 0 para lunes
		/**
		 * La documentacion oficial en el formato date
		 * toma como 1 el primer dia de la semana a partir de lunes
		 * 1.- lunes
		 * 2.- martes
		 * ...etc
		 */
		$numero = 0;
		switch ($weekday) {
			case 1:
				//lunes
				$numero = 0;
				break;
			case 2:
				//martes
				$numero = 1;
				break;
			case 3:
				//miercoles
				$numero = 2;
				break;
			case 4:
				//jueves
				$numero = 3;
				break;
			case 5:
				//viernes
				$numero = 4;
				break;
			case 6:
				//sabado
				$numero = 5;
				break;
			case 7:
				//domingo
				$numero = 6;
				break;
			default:
				$numero = 0;;
				break;
		}
		return $numero;
	}


	public static function calcularHoras($fecha, $horaInicio, $horaFin) {
		$date1 = date('Y-m-d H:i:s', strtotime($fecha . ' ' . $horaFin));
		//new DateTime($fecha.' T'.$horaFin);
		$date2 = date('Y-m-d H:i:s', strtotime($fecha . ' ' . $horaInicio));
		//new DateTime($fecha.' T'.$horaInicio);
		$ts1 = strtotime($date1);
		$ts2 = strtotime($date2);
		$diff = abs($ts1 - $ts2) / 3600;
		return $diff;
	}
}