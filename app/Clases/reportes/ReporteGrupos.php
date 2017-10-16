<?php
/**
 * Created by PhpStorm.
 * User: OSORIO
 * Date: 21/08/2016
 * Time: 02:13 PM
 */

namespace app\Clases\reportes;

use App\Clases\AsignacionClase;
use App\Clases\Carrera;
use App\Clases\Ciclo;
use App\Clases\Empleados;
use App\Clases\Grupo;

use Illuminate\Http\Request;

class ReporteGrupos {
	public static function getReporte(Request $request){
		$modalidad = $request->modalidad;
		$ciclo = $request->ciclo;
		switch ($modalidad){
			case 1:
				break;
			case 2:
				break;
			case 3:
				break;
		}
	}

	public static function esco(\fpdi\FPDI $fpdi, Grupo $grupo, Ciclo $ciclo, $asignacionClase,
								Carrera $carrera, AsignacionClase $objectClases) {
		$link = "components/pdf/esco.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		$fpdi->setXY(110, 27.5);
		//nombre del ciclo
		$fpdi->write(15, $ciclo->getNombre());
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(38, 39);
		//nombre del carrera
		$fpdi->write(10, $grupo->getGrado() . " " . $grupo->getGrupo() . "     " . iconv('UTF-8', 'windows-1252', $carrera->getNombre()));
		$fpdi->setFont('Arial', '', 8);
		$X = 15;
		$Y = 53;
		$totalHoras = 0;
		foreach ($asignacionClase as $item) {
			$fpdi->setXY($X, $Y);
			if (strlen($item->nombre) > 36)
				$fpdi->setFont('Arial', '', 6);
			else
				$fpdi->setFont('Arial', '', 8);
			$fpdi->write(10, iconv('UTF-8', 'windows-1252', $item->nombre));
			//obtenemos las clases asignadas para el grupo por materia
			$asigClase = $objectClases->getClasePorGrupo($grupo->getId(), $carrera->getId(), $ciclo->getId(), $item->clave_materias);
			//recorremos los resultados
			$i = 0;
			$horaInicio = "";
			$horaFin = "";
			$dia = 0;
			$checado = 0;
			$aun = 0;
			$no = 1;
			foreach ($asigClase as $valor) {
				if ($i == 0) {
					$empleado = new Empleados();
					$empleado->getSingleEmpleado($valor->id_empleado);
					$fpdi->setXY(82, $Y);
					$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . "  " . $empleado->getApellidos()));
					$horaInicio = substr($valor->hora_entrada, 0, 5);
					$i++;
				}
				$aun = 1;
				if ($valor->dia == $dia) {
					//no se que hacer aquí
					$horaFin = substr($valor->hora_salida, 0, 5);
					$no = 0;
				} else {
					switch ($dia) {
						case 0:
							$fpdi->setXY(150, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 1:
							$fpdi->setXY(170, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 2:
							$fpdi->setXY(190, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 3:
							$fpdi->setXY(213, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
					}
					$horaInicio = substr($valor->hora_entrada, 0, 5);
					$horaFin = substr($valor->hora_salida, 0, 5);
					$dia = $valor->dia;
					$no = 0;
				}

				switch ($dia) {
					case 0:
						$fpdi->setXY(150, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 1:
						$fpdi->setXY(170, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 2:
						$fpdi->setXY(190, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 3:
						$fpdi->setXY(213, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
				}

			}

			if ($no == 0) {
				switch ($dia) {
					case 0:
						$fpdi->setXY(150, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 1:
						$fpdi->setXY(170, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 2:
						$fpdi->setXY(190, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 3:
						$fpdi->setXY(213, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
				}
			}

			//total de horas
			$fpdi->setXY(232, $Y);
			$totalHoras += count($asigClase);
			$fpdi->write(10, count($asigClase));
			$Y += 10;
		}
		$fpdi->setFont('Arial', 'B', 8);
		$fpdi->setXY(232, 124);
		$fpdi->write(10, $totalHoras);
		$fpdi->Output();
	}
}