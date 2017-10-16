<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 26/07/16
 * Time: 12:21 PM
 */

namespace app\Clases\reportes;


use App\Clases\AsignacionClase;
use App\Clases\Carrera;
use App\Clases\Ciclo;
use App\Clases\Empleados;
use App\Clases\Grupo;
use App\Clases\Horarios;
use App\Clases\Materia;
use App\Clases\Utilerias;
use Illuminate\Http\Request;

/**
 * Class ReporteAsignaciones
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases\reportes
 */
class ReporteAsignaciones {

	/**
	 * Metodo para imprimir la asignacion de clase del grupo que se seleccione
	 * @param Request $request
	 */
	public static function imprimeGrupo(Request $request) {
		$grupo = new Grupo();
		$grupo->getGrupoOb($request->idGrupo);
		$carrera = new Carrera();
		$carrera->getCarrera($request->idCarrera);
		$asignacionClase = new AsignacionClase();
		$ciclo = new Ciclo();
		$ciclo->getCiclo($request->idCiclo);
		$response = $asignacionClase->getClaveMateriasGrupo($grupo->getId(), $carrera->getId(), $ciclo->getId());
		$fpdi = new \fpdi\FPDI('L', 'mm', 'A4');
		switch ($grupo->getIdModalidad()) {
			case 1:
				self::esco($fpdi, $grupo, $ciclo, $response, $carrera, $asignacionClase);
				break;
			case 2:
				self::semi($fpdi, $grupo, $ciclo, $response, $carrera, $asignacionClase);
				break;
		}
		exit;
	}

	/**
	 * Imprime la asignacion de clases para el grupo seleccionado escolarizado
	 * @param \fpdi\FPDI $fpdi
	 * @param Grupo $grupo
	 * @param Ciclo $ciclo
	 * @param $asignacionClase
	 * @param Carrera $carrera
	 * @param AsignacionClase $objectClases
	 */
	public static function esco(\fpdi\FPDI $fpdi, Grupo $grupo, Ciclo $ciclo, $asignacionClase,
								Carrera $carrera, AsignacionClase $objectClases) {
		$link = "components/pdf/esco.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		$fpdi->setXY(110, 21);
		//nombre del ciclo
		$fpdi->write(15, $ciclo->getNombre());
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(48, 32);
		//nombre del carrera
		$fpdi->write(10, $grupo->getGrado() . " " . $grupo->getGrupo() . "     " . iconv('UTF-8', 'windows-1252', $carrera->getNombre()));
		$fpdi->setXY(213, 32);
		$fpdi->write(10, date("Y-m-d h:i:s"));
		$fpdi->setFont('Arial', '', 8);
		$X = 15;
		$Y = 57;
		$totalHoras = 0;
		$fecha = date('Y-m-d');
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
			$thorasD = 0;
			$no = 1;
			$fpdi->setFont('Arial', '', 8);
			foreach ($asigClase as $valor) {
				if ($i == 0) {
					$empleado = new Empleados();
					$empleado->getSingleEmpleado($valor->id_empleado);
					$fpdi->setXY(75, $Y);
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
							$fpdi->setXY(165, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 1:
							$fpdi->setXY(182, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 2:
							$fpdi->setXY(200, $Y + 4);
							$fpdi->write(10, $horaFin);
							break;
						case 3:
							$fpdi->setXY(218, $Y + 4);
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
						$fpdi->setXY(165, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 1:
						$fpdi->setXY(182, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 2:
						$fpdi->setXY(200, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
					case 3:
						$fpdi->setXY(218, $Y);
						$fpdi->write(10, $horaInicio . " -");
						break;
				}


				$thorasD += Utilerias::calcularHoras($fecha, $valor->hora_entrada, $valor->hora_salida);
			}

			if ($no == 0) {
				switch ($dia) {
					case 0:
						$fpdi->setXY(165, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 1:
						$fpdi->setXY(182, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 2:
						$fpdi->setXY(200, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
					case 3:
						$fpdi->setXY(218, $Y + 4);
						$fpdi->write(10, $horaFin);
						break;
				}
			}

			//total de horas
			$fpdi->setXY(237, $Y);
			$totalHoras += $thorasD;
			$fpdi->write(10, $thorasD);
			$Y += 9;
		}
		$fpdi->setFont('Arial', 'B', 8);
		$fpdi->setXY(237, 120);
		$fpdi->write(10, $totalHoras);
		$fpdi->setTitle("Carga Escolarizada");
		$fpdi->Output("Carga Escolarizada.pdf","I");
	}

	/**
	 * Imprime la asignacion de clases para el grupo seleccionado escolarizado
	 * @param \fpdi\FPDI $fpdi
	 * @param Grupo $grupo
	 * @param Ciclo $ciclo
	 * @param $asignacionClase
	 * @param Carrera $carrera
	 * @param AsignacionClase $objectClases
	 */
	public static function semi(\fpdi\FPDI $fpdi, Grupo $grupo, Ciclo $ciclo, $asignacionClase, Carrera $carrera
		, AsignacionClase $objectClases) {
		$link = "components/pdf/semiescolarizado.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		$fpdi->setXY(117, 23);
		//nombre del ciclo
		$fpdi->write(15, $ciclo->getNombre());
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(53, 35);
		//nombre del carrera
		$fpdi->write(10, $grupo->getGrado() . " " . $grupo->getGrupo() . "     " . iconv('UTF-8', 'windows-1252', $carrera->getNombre()));
		$fpdi->setXY(215, 35);
		$fpdi->write(10, date("Y-m-d h:i:s"));
		$fpdi->setFont('Arial', 'B', 9);
		$X = 15;
		$Y = 64;
		$totalHoras = 0;
		//BLOQUE SABADOS
		$fpdi->setXY(195, 57);
		$fpdi->write(10, "SABADOS");
		$fpdi->setFont('Arial', '', 8);
		foreach ($asignacionClase as $item) {
			//obtenemos las clases asignadas para el grupo por materia
			$asigClase = $objectClases->getClasePorGrupoSemi($grupo->getId(), $carrera->getId(), $ciclo->getId(), $item->clave_materias,5);
			//recorremos los resultados
			$i = 0;
			$horaInicio = "";
			$horaFin = "";
			$dia = 0;
			$checado = 0;
			$aun = 0;
			$no = 1;
			foreach ($asigClase as $valor) {
				if ($valor->dia = 5){
					if ($i == 0) {
						$fpdi->setXY($X, $Y);
						if (strlen($item->nombre) > 36)
							$fpdi->setFont('Arial', '', 6);
						else
							$fpdi->setFont('Arial', '', 8);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $item->nombre));
						$empleado = new Empleados();
						$empleado->getSingleEmpleado($valor->id_empleado);
						$fpdi->setXY(83, $Y);
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
							case 5:
								$fpdi->setXY(205, $Y);
								$fpdi->write(10, $horaFin);
								break;
						}
						$horaInicio = substr($valor->hora_entrada, 0, 5);
						$horaFin = substr($valor->hora_salida, 0, 5);
						$dia = $valor->dia;
						$no = 0;
					}

					switch ($dia) {
						case 5:
							$fpdi->setXY(185, $Y);
							$fpdi->write(10, $horaInicio . " -");
							break;

					}
				}


			}

			if ($no == 0) {
				switch ($dia) {
					case 5:
						$fpdi->setXY(205, $Y);
						$fpdi->write(10, $horaFin);
						break;
				}
			}

			//total de horas
			$fpdi->setXY(230, $Y);
			$totalHoras += count($asigClase);
			if (count($asigClase)>0)
				$fpdi->write(10, count($asigClase));
			$Y += 10;
		}
		$fpdi->setFont('Arial', 'B', 8);
		$fpdi->setXY(230, 137);
		$fpdi->write(10, $totalHoras);

		//END BLOQUE SABADOS
		//DOMINGOS
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		$fpdi->setXY(117, 23);
		//nombre del ciclo
		$fpdi->write(15, $ciclo->getNombre());
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(53, 35);
		//nombre del carrera
		$fpdi->write(10, $grupo->getGrado() . " " . $grupo->getGrupo() . "     " . iconv('UTF-8', 'windows-1252', $carrera->getNombre()));
		$fpdi->setXY(215, 35);
		$fpdi->write(10, date("Y-m-d h:i:s"));
		$fpdi->setFont('Arial', 'B', 9);
		$X = 15;
		$Y = 64;
		$totalHoras = 0;
		//BLOQUE SABADOS
		$fpdi->setXY(195, 57);
		$fpdi->write(10, "DOMINGOS");
		$fpdi->setFont('Arial', '', 8);
		foreach ($asignacionClase as $item) {
			//obtenemos las clases asignadas para el grupo por materia
			$asigClase = $objectClases->getClasePorGrupoSemi($grupo->getId(), $carrera->getId(), $ciclo->getId(), $item->clave_materias,6);
			//recorremos los resultados
			$i = 0;
			$horaInicio = "";
			$horaFin = "";
			$dia = 0;
			$checado = 0;
			$aun = 0;
			$no = 1;
			foreach ($asigClase as $valor) {
				if ($valor->dia = 6){
					if ($i == 0) {
						$fpdi->setXY($X, $Y);
						if (strlen($item->nombre) > 36)
							$fpdi->setFont('Arial', '', 6);
						else
							$fpdi->setFont('Arial', '', 8);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $item->nombre));
						$empleado = new Empleados();
						$empleado->getSingleEmpleado($valor->id_empleado);
						$fpdi->setXY(83, $Y);
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
							case 6:
								$fpdi->setXY(205, $Y);
								$fpdi->write(10, $horaFin);
								break;
						}
						$horaInicio = substr($valor->hora_entrada, 0, 5);
						$horaFin = substr($valor->hora_salida, 0, 5);
						$dia = $valor->dia;
						$no = 0;
					}

					switch ($dia) {
						case 6:
							$fpdi->setXY(185, $Y);
							$fpdi->write(10, $horaInicio . " -");
							break;

					}
				}


			}

			if ($no == 0) {
				switch ($dia) {
					case 6:
						$fpdi->setXY(205, $Y);
						$fpdi->write(10, $horaFin);
						break;
				}
			}

			//total de horas
			$fpdi->setXY(230, $Y);
			$totalHoras += count($asigClase);
			if (count($asigClase)>0) {
				$fpdi->write(10, count($asigClase));
				$Y += 10;
			}
		}
		$fpdi->setFont('Arial', 'B', 8);
		$fpdi->setXY(230, 137);
		$fpdi->write(10, $totalHoras);

		//END BLOQUE SABADOS
		$fpdi->setTitle("Carga Semi");
		$fpdi->Output("Carga Semi.pdf","I");
	}

	/**
	 * Metodo para generar el reporte de carga academica del docente en forma individual
	 * @param $idDocente
	 */
	public static function generarCarga($idDocente, $idCiclo) {
		//obtenemos el ciclo activo
		$ciclos = new Ciclo();
		$ciclo = $ciclos->getCiclo($idCiclo);
		$fpdi = new \fpdi\FPDI('L', 'mm', 'A4');
		$link = "components/pdf/carga.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($idDocente);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		//ciclo
		$fpdi->setXY(115, 21.5);
		$fpdi->write(10, $ciclo->nombre);
		//nombre
		$fpdi->setXY(28, 31);
		$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . " " . $empleado->getApellidos()));
		$horarios = Horarios::getCargaAcademica($idDocente,$idCiclo);
		$fpdi->setFont('Arial', '', 8);
		$X = 15;
		$Y = 40;
		$idmateria = 0;
		$idgrupo = 0;
		$totalHs = 0;
		$total = 0;
		$boleano = true;
		$contador = 0;
		$idCarrera = 0;
		foreach ($horarios as $horario) {
			if ($contador > 10) {
				$fpdi->addPage();
				$fpdi->useTemplate($tplIdx, 0, 0);
				$fpdi->setFont('Arial', 'B', 11);
				//ciclo
				$fpdi->setXY(115, 21.5);
				$fpdi->write(10, $ciclo->nombre);
				//nombre
				$fpdi->setXY(28, 31);
				$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . " " . $empleado->getApellidos()));
				$horarios = Horarios::getCargaAcademica($idDocente,$idCiclo);
				$fpdi->setFont('Arial', '', 8);
				$X = 15;
				$Y = 40;
				$contador = 0;
			}
			$materia = new Materia();
			$materia->getMateria($horario->clave_materias);
			$fecha = date('Y-m-d');
			$grupos = new Grupo();
			$grupos->getGrupoOb($horario->id_grupos);
			$carreras = new Carrera();
			$carreras->getCarrera($horario->id_carreras);
			if ($idgrupo != $grupos->getId() || $idmateria != $horario->clave_materias) {
				$idCarrera = $horario->id_carreras;
				$idgrupo = $grupos->getId();
				$idmateria = $horario->clave_materias;
				$boleano = true;
				if ($totalHs > 0) {
					$fpdi->setXY(255, $Y);
					$fpdi->write(10, $totalHs);
					$totalHs = 0;
					$boleano = false;
				}
				$contador++;
				$Y += self::getSalto($contador);
				$fpdi->setXY($X, $Y);
				$fpdi->write(10, iconv('UTF-8', 'windows-1252', $materia->getNombre()));
			}
			$fpdi->setXY(85, $Y - 1);
			$fpdi->setFont('Arial', '', 6.5);
			$fpdi->write(10, $grupos->getGrado() . " " . $grupos->getGrupo());
			$fpdi->setXY(85, $Y + 3);
			$fpdi->write(10, iconv('UTF-8', 'windows-1252', $carreras->getNombre()));
			$fpdi->setFont('Arial', '', 8);
			switch ($horario->dia) {
				case 0:
					$fpdi->setXY(155, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(155, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 1:
					$fpdi->setXY(172, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(172, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 2:
					$fpdi->setXY(190, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(190, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 3:
					$fpdi->setXY(205, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(205, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 5:
					$fpdi->setXY(220, $Y);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(220, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 6:
					$fpdi->setXY(236, $Y);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(236, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
			}

			$totalHs += Utilerias::calcularHoras($fecha, $horario->hora_entrada, $horario->hora_salida);
			$total += Utilerias::calcularHoras($fecha, $horario->hora_entrada, $horario->hora_salida);
			/*echo $horario->dia . " " . $grupos->getGrado() . " " . $grupos->getGrupo() . " ";
			echo $carreras->getNombre() . " ";
			echo $horario->id_asignacion_horario . " " . $materia->getNombre() . " ";
			echo $grupos->getId()." ".$materia->getClave();
			echo $horario->hora_entrada . " ";
			echo $horario->hora_salida . "<br>";*/


		}
		if (!$boleano) {
			$fpdi->setXY(255, $Y);
			$fpdi->write(10, $totalHs);
		}
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(255, 173);
		$fpdi->write(10, $total);
		$fpdi->setTitle($empleado->getNombre() . " " . $empleado->getApellidos());
		$fpdi->Output($empleado->getNombre() . " " . $empleado->getApellidos().".pdf","I");
	}

	public static function generarCarga2($idDocente, $idCiclo) {
		//obtenemos el ciclo activo
		$ciclos = new Ciclo();
		$ciclo = $ciclos->getCiclo($idCiclo);
		$fpdi = new \fpdi\FPDI('L', 'mm', 'A4');
		$link = "components/pdf/carga.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($idDocente);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 11);
		//ciclo
		$fpdi->setXY(115, 21.5);
		$fpdi->write(10, $ciclo->nombre);
		//nombre
		$fpdi->setXY(28, 31);
		$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . " " . $empleado->getApellidos()));
		$horarios = Horarios::obtCargaAcademica($idDocente, $idCiclo);
		//$cargaDoc = Horarios::obtCargaDocente($idDocente, $idCiclo);
		$fpdi->setFont('Arial', '', 8);
		$X = 15;
		$Y = 40;
		$totalHs = 0;
		$total = 0;
		$bandera = false;
		$contador = 0;
		$clvmat = '';
		$ypos = [50, 60, 73, 83, 93, 106, 116, 129, 139, 152, 162];
		foreach ($horarios as $horario) {
			if ($contador == 11 && $clvmat != $horario->clv_materia) { //Es la ultima línea y la materia no ha cambiado
				$fpdi->addPage();
				$fpdi->useTemplate($tplIdx, 0, 0);
				$fpdi->setFont('Arial', 'B', 11);
				//ciclo
				$fpdi->setXY(115, 21.5);
				$fpdi->write(10, $ciclo->nombre);
				//nombre
				$fpdi->setXY(28, 31);
				$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . " " . $empleado->getApellidos()));
				$fpdi->setFont('Arial', '', 8);
				$X = 15;
				$contador = 0;
			}
			if($clvmat != $horario->clv_materia) {
				$Y = $ypos[$contador];
				$contador++;
				$fpdi->setXY($X, $Y);
				$fpdi->write(10, iconv('UTF-8', 'windows-1252', $horario->nom_materia));
				$clvmat = $horario->clv_materia;
				$fpdi->setXY(85, $Y - 1);
				$fpdi->setFont('Arial', '', 6.5);
				$fpdi->write(10, $horario->nom_grupo);
				$fpdi->setXY(85, $Y + 3);
				$fpdi->write(10, iconv('UTF-8', 'windows-1252', $horario->nom_carrera));
				$fpdi->setFont('Arial', '', 8);
				$hrsmat = Horarios::obtHoras($idDocente, $idCiclo, $clvmat);
				$totalHs = $hrsmat[0]->totalhrs;
				$fpdi->setXY(255, $Y);
				$fpdi->write(10, $totalHs);
			}
			switch ($horario->dia) {
				case 0:
					$fpdi->setXY(155, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(155, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 1:
					$fpdi->setXY(172, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(172, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 2:
					$fpdi->setXY(190, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(190, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 3:
					$fpdi->setXY(205, $Y - 2);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(205, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 5:
					$fpdi->setXY(220, $Y);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(220, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
				case 6:
					$fpdi->setXY(236, $Y);
					$fpdi->write(10, $horario->hora_entrada);
					$fpdi->setXY(236, $Y + 3);
					$fpdi->write(10, $horario->hora_salida);
					break;
			}
			$total += $horario->horas;			
		}		
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(255, 173);
		$fpdi->write(10, $total);
		$fpdi->setTitle($empleado->getNombre() . " " . $empleado->getApellidos());
		$fpdi->Output($empleado->getNombre() . " " . $empleado->getApellidos().".pdf","I");
	}

	public static function getSalto($contador){
		$valor = 10;
		switch ($contador){
			case 3:
				$valor=13;
				break;
			case 6:
				$valor=13;
				break;
			case 8:
			case 10:
				$valor=13;
				break;
			default:
				break;
		}
		return $valor;
	}
}