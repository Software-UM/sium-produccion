<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 27/07/16
 * Time: 12:38 PM
 */

namespace app\Clases\reportes;

use App\Clases\AsignacionHorario;
use App\Clases\Asistencias;
use App\Clases\Ciclo;
use App\Clases\Empleados;
use App\Clases\Horarios;

/**
 * Class ReporteDocente
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package app\Clases\reportes
 */
class ReporteDocente {
	/**
	 * Imprime las horas de proyeccion docente
	 * @param $modalidad
	 * @param $idCiclo
	 */
	public static function imprimeGeneral($modalidad,$cct) {
		$ciclos = new Ciclo();
		$ciclo = $ciclos->getCicloActivo();
		//mandamos a llamar la creacion del reporte
		$empleados = Empleados::reporteDocenteGeneral($ciclo[0]['id'], $modalidad,$cct);
		$ciclos->getCiclo($ciclo[0]['id']);
		self::esco($ciclos, $empleados, $modalidad);
	}

	/**
	 * Imprime el reporte de proyeccion docente en PDF
	 * @param Ciclo $ciclo
	 * @param $empleados
	 * @param $modalidad
	 */
	public static function esco(Ciclo $ciclo, $empleados, $modalidad) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/docente.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(70, 30);
		//nombre del ciclo
		$fpdi->write(10, $ciclo->getNombre());
		$fpdi->setFont('Arial', 'B', 10);
		$fpdi->setXY(70, 35);
		//nombre del carrera
		$fpdi->write(10, date("Y-m-d h:i:s"));
		$fpdi->setFont('Arial', '', 8);
		$X = 20;
		$Y = 48;
		$totalHoras = 0;
		$i = 0;
		foreach ($empleados as $empleado) {
			if ($i > 47 && $i < 49 || $i > 95 && $i < 97) {
				$fpdi->addPage();
				$fpdi->useTemplate($tplIdx, 0, 0);
				$fpdi->setFont('Arial', 'B', 10);
				$fpdi->setXY(70, 31);
				//nombre del ciclo
				$fpdi->write(10, $ciclo->getNombre());
				$fpdi->setFont('Arial', 'B', 10);
				$fpdi->setXY(70, 36);
				//nombre del carrera
				$fpdi->write(10, date("Y-m-d h:i:s"));
				$fpdi->setFont('Arial', '', 8);
				$X = 20;
				$Y = 48;
			}
			$fpdi->setFont('Arial', 'B', 8);
			$fpdi->setXY($X, $Y);
			$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->nombre));
			$fpdi->setXY(150, $Y);
			$fpdi->write(10, $empleado->id);
			$totalHoras += $empleado->id;
			$Y += self::saltos($i);
			$i++;
			//$Y += 4.5;
		}
		$fpdi->setFont('Arial', 'B', 8);
		$fpdi->setXY(105, 253);
		$fpdi->write(10, "TOTAL HORAS: ");
		$fpdi->setXY(150, 253);
		$fpdi->write(10, $totalHoras);
		$fpdi->setTitle("Reporte General");
		$fpdi->Output('ReporteGeneral.pdf','I',false);
	}

	/**
	 * Imprime el horario general de asistencia administrativo
	 * @param $fechaInicio
	 * @param $fechaFin
	 */
	public static function imprimeAdmon($fechaInicio, $fechaFin,$cct) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/admon.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->SetTitle('Reporte Administrativo');
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));
		$empleados = Empleados::getAdmons($cct);
		foreach ($empleados AS $empleado) {
			$fpdi->addPage();
			$fpdi->useTemplate($tplIdx, 0, 0);
			$fpdi->setFont('Arial', 'B', 8);
			//nombre
			$fpdi->setXY(62, 25.5);
			$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->nombre));
			//Fecha entrada y salida
			$fpdi->setXY(68, 28.5);
			$fpdi->write(10, $fechaInicio);
			$fpdi->setXY(65, 32);
			$fpdi->write(10, $fechaFin);
			//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
			$X = 27;
			$Y = 44;
			//variables
			$restardos = 0;
			$asistencias = 0;
			$subtotal = 0;
			$total = 0;
			$faltas = 0;
			$countados = 0;
			$fpdi->setFont('Arial', '', 8);
			for ($i = 0; $i <= $dias; $i++) {
				//fecha a consultar
				$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
				$weekday = date('N', strtotime($fechaTomada));
				$diaConsultar = self::getDiaBD($weekday);
				//checamos primero si el usuario tiene hora asignada
				$horarios = AsignacionHorario::getHorarioPersonalDia($diaConsultar, $empleado->id);
				if (count($horarios) > 0) {
					if ($countados == 32) {
						$fpdi->addPage();
						$fpdi->useTemplate($tplIdx, 0, 0);
						$fpdi->setFont('Arial', 'B', 8);
						//nombre
						$fpdi->setXY(62, 25.5);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->nombre));
						//Fecha entrada y salida
						$fpdi->setXY(68, 28.5);
						$fpdi->write(10, $fechaInicio);
						$fpdi->setXY(65, 32);
						$fpdi->write(10, $fechaFin);
						//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
						$X = 27;
						$Y = 44;
						//variables
						$countados = 0;
						$fpdi->setFont('Arial', '', 8);
					}
					$total++;
					$fpdi->setXY($X, $Y);
					$fpdi->write(10, self::getNombreDia($weekday));
					$fpdi->setXY(50, $Y);
					$fpdi->write(10, date('d-m-Y', strtotime($fechaInicio . ' +' . $i . ' day')));
					foreach ($horarios as $horario) {
						if ($countados == 32) {
							$fpdi->addPage();
							$fpdi->useTemplate($tplIdx, 0, 0);
							$fpdi->setFont('Arial', 'B', 8);
							//nombre
							$fpdi->setXY(62, 25.5);
							$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->nombre));
							//Fecha entrada y salida
							$fpdi->setXY(68, 28.5);
							$fpdi->write(10, $fechaInicio);
							$fpdi->setXY(65, 32);
							$fpdi->write(10, $fechaFin);
							//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
							$X = 27;
							$Y = 44;
							//variables
							$countados = 0;
							$fpdi->setFont('Arial', '', 8);
						}
						//echo date_diff(strtotime($horario->hora_entrada),strtotime($horario->hora_salida));
						$entradaint = intval($horario->hora_entrada);
						$salidaint = intval($horario->hora_salida);
						if (($salidaint - $entradaint) > 2) {
							$fpdi->setXY(80, $Y);
							//$fpdi->write(10, $horario->hora_entrada);
							//VERIFICAMOS EL CHEQUEO ANTERIOR
							$valor = Asistencias::getAsistenciaPersonal($horario->idA, $empleado->id, $fechaTomada);
							if (count($valor) > 0) {
								$fpdi->write(10, $valor[0]['hora_llegada']);
								$fpdi->setXY(100, $Y);
								if ($valor[0]['id_estado'] == 1) {
									$restardos += 1;
									$fpdi->write(10, "RETARDO");
								} else if ($valor[0]['id_estado'] == 2) {
									$fpdi->write(10, "A TIEMPO");
								} else {
									$fpdi->write(10, "POR INCIDENCIA");
								}
								if (!empty($valor[0]['hora_salida'])){
									$fpdi->setXY(140, $Y);
									$fpdi->write(10, self::generaHorarioRandom($horario->hora_salida));
								}

								$fpdi->setXY(165, $Y);
								if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
									//mandamos algo de falta ya que no cumplio con el chequeo
									$fpdi->write(10, 'FALTA');
									$faltas++;
								} else {
									$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $horario->hora_salida);
									if ($valor >= 7.70) {
										$asistencias += 1;
										$fpdi->write(10, 'ASISTENCIA');
									} else {
										//$restardos += 1;
										$asistencias += 1;
										$fpdi->write(10, 'RETARDO');
									}
								}
							} else {
								$fpdi->setXY(165, $Y);
								$fpdi->write(10, 'NO CHECO');
								$faltas++;
							}
							$countados++;
							$Y += self::saltos2($i);

						}

						if (($salidaint - $entradaint) < 2) {
							$fpdi->setXY(80, $Y);
							//$fpdi->write(10, $horario->hora_entrada);
							//VERIFICAMOS EL CHEQUEO ANTERIOR
							$valor = Asistencias::getAsistenciaPersonal($horario->idA, $empleado->id, $fechaTomada);
							if (count($valor) > 0) {
								$fpdi->write(10, $valor[0]['hora_salida']);
								$fpdi->setXY(100, $Y);
								if ($valor[0]['id_estado'] == 1) {
									$restardos += 1;
									$fpdi->write(10, "RETARDO");
								} else if ($valor[0]['id_estado'] == 2) {
									$fpdi->write(10, "A TIEMPO");
								} else {
									$fpdi->write(10, "POR INCIDENCIA");
								}
								$fpdi->setXY(140, $Y);
								$fpdi->write(10, $valor[0]['hora_llegada']);
								$fpdi->setXY(165, $Y);
								if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
									//mandamos algo de falta ya que no cumplio con el chequeo
									$fpdi->write(10, 'FALTA');
									$faltas++;
								} else {
									$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $valor[0]['hora_salida']);
									if ($valor >= 0.50) {
										$asistencias += 1;
										$fpdi->write(10, 'ASISTENCIA');
									} else {
										//$restardos += 1;
										$asistencias += 1;
										$fpdi->write(10, 'RETARDO');
									}
								}
							} else {
								$fpdi->setXY(165, $Y);
								$fpdi->write(10, 'NO CHECO');
								$faltas++;
							}
							$countados++;
							$Y += self::saltos2($i);
						}

					}
				}
			}
			//HACEMOS LOS SUBTOTALES GENERADOS
			$fpdi->setXY(50, 238);
			$fpdi->write(10, $faltas);
			$fpdi->setXY(100, 238);
			$fpdi->write(10, $restardos);
			$subretardos = 0;
			if ($restardos > 2) {
				$subretardos = $restardos / 3;
			}

			$fpdi->setXY(165, 238);
			$fpdi->write(10, $asistencias);
			$fpdi->setXY(165, 249);
			$fpdi->write(10, $asistencias - round($subretardos));
		}
		$fpdi->setTitle("Reporte Administrativo");
		$fpdi->Output('Reporte Administrativo.pdf','I');
	}

	/**
	 * Imprime el horario general de asistencia dle docente,
	 * Dependiendo de su carga horaria
	 * @param $fechaInicio
	 * @param $fechaFin
	 * @param $ciclo
	 * @param $plantel
	 */
	public static function imprimeDocente($fechaInicio, $fechaFin, $ciclo, $plantel) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/docente-chequeo.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		//los dias a tomar
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));
		//Obtenermos primero los docentes
		$empleados = new Empleados();
		$docentes = $empleados->getDocentes($plantel);
		foreach ($docentes as $docente) {
			$retardos = 0;
			$horasTrabajadas = 0;
			$horasAsignadas = 0;
			$subtotal = 0;
			$total = 0;
			$faltas = 0;
			$find = 0;
			$X = 27;
			$Y = 42;
			$jj = 0;
			for ($i = 0; $i <= $dias; $i++) {
				//CHECAMOS DIA POR DIA LA ASIGNACION DE HORAS QUE TIENEN
				//fecha a consultar
				$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
				$weekday = date('N', strtotime($fechaTomada));
				$diaConsultar = self::getDiaBD($weekday);
				$horarios = Horarios::getHorariClase($docente->id, $diaConsultar);
				//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
				if (count($horarios) > 0) {
					if ($find == 0) {
						$fpdi->addPage();
						$fpdi->useTemplate($tplIdx, 0, 0);
						$fpdi->setFont('Arial', 'B', 8);
						//nombre
						$fpdi->setXY(62, 25.5);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $docente->nombre . " " . $docente->apellidos));
						//Fecha entrada y salida
						$fpdi->setXY(68, 28.5);
						$fpdi->write(10, $fechaInicio);
						$fpdi->setXY(65, 32);
						$fpdi->write(10, $fechaFin);
						//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
						$find = 1;
					}
					$fpdi->setFont('Arial', '', 8);
					$fpdi->setXY($X, $Y);
					$fpdi->write(10, self::getNombreDia($weekday));
					$fpdi->setXY(53, $Y);
					$fpdi->write(10, date('d-m-Y', strtotime($fechaInicio . ' +' . $i . ' day')));
					//aqui aumentamos el dia ya que si tiene hora laboral
					$total++;
					foreach ($horarios as $horario) {
						if ($jj >= 31 && $jj<=31) {
							$fpdi->addPage();
							$fpdi->useTemplate($tplIdx, 0, 0);
							$fpdi->setFont('Arial', 'B', 8);
							//nombre
							$fpdi->setXY(62, 25.5);
							$fpdi->write(10, iconv('UTF-8', 'windows-1252', $docente->nombre . " " . $docente->apellidos));
							//Fecha entrada y salida
							$fpdi->setXY(68, 28.5);
							$fpdi->write(10, $fechaInicio);
							$fpdi->setXY(65, 32);
							$fpdi->write(10, $fechaFin);
							$Y = 42;
							$jj = 0;
						}
						$valor = Asistencias::getAsistenciaPersonal($horario->id_asignacion_horario, $docente->id, $fechaTomada);
						//vemos si cuenta con regitro
						if (count($valor)) {
							$fpdi->setXY(78, $Y);
							$fpdi->write(10, $valor[0]['hora_llegada']);
							$fpdi->setXY(125, $Y);
							if ($valor[0]['id_estado'] == 1) {
								$fpdi->write(10, "RETARDO");
								$retardos++;
							} else if ($valor[0]['id_estado'] == 2) {
								$fpdi->write(10, "A TIEMPO");
							} else {
								$fpdi->setFont('Arial', '', 6);
								$fpdi->setXY(124, $Y);
								$fpdi->write(10, "POR INCIDENCIA");
								$fpdi->setFont('Arial', '', 8);
							}
							$fpdi->setXY(100, $Y);
							$fpdi->write(10, $valor[0]['hora_salida']);
							$fpdi->setXY(180, $Y);
							if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
								//mandamos algo de falta ya que no cumplio con el chequeo
								$fpdi->setXY(176, $Y);
								$fpdi->write(10, 'FALTA');
								$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
								$horasAsignadas+= $valor2;
								$fpdi->setXY(153, $Y);
								$fpdi->write(10, $valor2);
								$faltas += $valor2;
							} else {
								//calculamos horas asignadas
								$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
								$horasAsignadas+= $valor2;
								$fpdi->setXY(153, $Y);
								$fpdi->write(10, $valor2);
								//calculamos las horas trabajadas
								$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $valor[0]['hora_salida']);
								$horasTrabajadas += round($valor);
								$fpdi->setXY(180, $Y);
								$fpdi->write(10, round($valor));
							}
						} else {
							$fpdi->setXY(80, $Y);
							$fpdi->write(10, "-----");
							$fpdi->setXY(105, $Y);
							$fpdi->write(10, "-----");
							$fpdi->setXY(127, $Y);
							$fpdi->write(10, "-----");
							$fpdi->setXY(153, $Y);
							$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
							$horasAsignadas+= $valor2;
							$fpdi->write(10, $valor2);
							$fpdi->setXY(180, $Y);
							$fpdi->write(10, 0);
							$horasTrabajadas += 0;
							$faltas += $valor2;
						}
						$jj++;
						$Y += self::saltos2($jj);
					}

					//$Y += self::saltos2($i);
				}

			}
			if ($find == 1) {
				//HACEMOS LOS SUBTOTALES GENERADOS
				$fpdi->setFont('Arial', '', 8);
				$fpdi->setXY(50, 231);
				$fpdi->Write(10, $faltas);
				$fpdi->setXY(100, 231);
				$fpdi->write(10, $retardos);
				$subretardos = 0;
				if ($retardos > 2) {
					$subretardos = $retardos / 3;
				}
				$fpdi->setXY(150,226);
				$fpdi->Write(10, $horasAsignadas);
				$fpdi->setXY(175, 231);
				$fpdi->Write(10, $horasTrabajadas);
				$fpdi->setXY(175, 242);
				$fpdi->Write(10, $horasTrabajadas - $subretardos);
			}
		}
		$fpdi->setTitle("Concentrado");
		$fpdi->Output('Concentrado.pdf','I');
	}

	/**
	 * Imprime la asistencia general de desayunos para administrativos
	 * @param $fechaInicio
	 * @param $fechaFin
	 */
	public static function imprimeDesayunos($fechaInicio, $fechaFin,$cct) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/admon.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->SetTitle('Reporte Desayunos');
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));
		$empleados = Empleados::getAdmons($cct);
		foreach ($empleados AS $empleado) {
			$fpdi->addPage();
			$fpdi->useTemplate($tplIdx, 0, 0);
			$fpdi->setFont('Arial', 'B', 8);
			//nombre
			$fpdi->setXY(62, 25.5);
			$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->nombre));
			//Fecha entrada y salida
			$fpdi->setXY(68, 28.5);
			$fpdi->write(10, $fechaInicio);
			$fpdi->setXY(65, 32);
			$fpdi->write(10, $fechaFin);
			//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
			$X = 27;
			$Y = 44;
			//variables
			$restardos = 0;
			$asistencias = 0;
			$subtotal = 0;
			$total = 0;
			$faltas = 0;
			$fpdi->setFont('Arial', '', 8);
			for ($i = 0; $i <= $dias; $i++) {
				//fecha a consultar
				$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
				$weekday = date('N', strtotime($fechaTomada));
				$diaConsultar = self::getDiaBD($weekday);
				//checamos primero si el usuario tiene hora asignada
				$horarios = AsignacionHorario::getHorarioPersonalDia($diaConsultar, $empleado->id);
				if (count($horarios) > 0) {
					$total++;
					$fpdi->setXY($X, $Y);
					$fpdi->write(10, self::getNombreDia($weekday));
					$fpdi->setXY(50, $Y);
					$fpdi->write(10, date('d-m-Y', strtotime($fechaInicio . ' +' . $i . ' day')));
					foreach ($horarios as $horario) {
						//echo date_diff(strtotime($horario->hora_entrada),strtotime($horario->hora_salida));
						$entradaint = intval($horario->hora_entrada);
						$salidaint = intval($horario->hora_salida);
						if (($salidaint - $entradaint) < 2) {
							$fpdi->setXY(80, $Y);
							//$fpdi->write(10, $horario->hora_entrada);
							//VERIFICAMOS EL CHEQUEO ANTERIOR
							$valor = Asistencias::getAsistenciaPersonal($horario->idA, $empleado->id, $fechaTomada);
							if (count($valor) > 0) {
								$fpdi->write(10, $valor[0]['hora_salida']);
								$fpdi->setXY(100, $Y);
								if ($valor[0]['id_estado'] == 1) {
									$restardos += 1;
									$fpdi->write(10, "RETARDO");
								} else if ($valor[0]['id_estado'] == 2) {
									$fpdi->write(10, "A TIEMPO");
								} else {
									$fpdi->write(10, "POR INCIDENCIA");
								}
								$fpdi->setXY(140, $Y);
								$fpdi->write(10, $valor[0]['hora_llegada']);
								$fpdi->setXY(165, $Y);
								if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
									//mandamos algo de falta ya que no cumplio con el chequeo
									$fpdi->write(10, 'FALTA');
									$faltas++;
								} else {
									$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $valor[0]['hora_salida']);
									if ($valor >= 0.50) {
										$asistencias += 1;
										$fpdi->write(10, 'ASISTENCIA');
									} else {
										//$restardos += 1;
										$asistencias += 1;
										$fpdi->write(10, 'RETARDO');
									}
								}
							} else {
								$fpdi->setXY(165, $Y);
								$fpdi->write(10, 'NO CHECO');
								$faltas++;
							}


						}

					}

					$Y += self::saltos2($i);
				}
			}
			//HACEMOS LOS SUBTOTALES GENERADOS
			$fpdi->setXY(50, 238);
			$fpdi->write(10, $faltas);
			$fpdi->setXY(100, 238);
			$fpdi->write(10, $restardos);
			/*$subretardos = 0;
			if ($restardos > 2) {
				$subretardos = $restardos / 3;
			}

			$fpdi->setXY(165, 238);
			$fpdi->write(10, $asistencias);
			$fpdi->setXY(165, 249);
			$fpdi->write(10, $asistencias - round($subretardos));*/
		}
		$fpdi->setTitle("Desayunos");
		$fpdi->Output('Desayunos.pdf','I');
	}

	/**
	 * Hace un reporte muy general de los horarios de los docentes
	 * @param $fechaInicio
	 * @param $fechaFin
	 * @param $ciclo
	 * @param $plantel
	 */
	public static function imprimeConcentrado($fechaInicio, $fechaFin, $ciclo, $plantel) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/reporte-asistencia-general2.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		//los dias a tomar
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));

		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 8);
		//Fecha entrada y salida
		$fpdi->setXY(70, 29.5);
		$fpdi->write(10, $fechaInicio);
		$fpdi->setXY(160, 29.5);
		$fpdi->write(10, $fechaFin);
		//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
		$find = 1;

		//Obtenermos primero los docentes
		$empleados = new Empleados();
		$docentes = $empleados->getDocentes($plantel);
		$X = 27;
		$Y = 48;
		//Variables para hacer el concentrado de totales
		$totalFaltas = 0;
		$totalRetardos = 0;
		$totalIncidencias = 0;
		$totalHorasTrabajadas = 0;
		$totalHorasPagadas = 0;
		$fpdi->setFont('Arial', '', 8);
		$contador = 0;
		foreach ($docentes as $docente) {
			$retardos = 0;
			$horasTrabajadas = 0;
			$incidencia = 0;
			$subtotal = 0;
			$total = 0;
			$faltas = 0;
			$find = 0;
			for ($i = 0; $i <= $dias; $i++) {
				//CHECAMOS DIA POR DIA LA ASIGNACION DE HORAS QUE TIENEN
				//fecha a consultar
				$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
				$weekday = date('N', strtotime($fechaTomada));
				$diaConsultar = self::getDiaBD($weekday);
				$horarios = Horarios::getHorariClase($docente->id, $diaConsultar);
				//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
				if (count($horarios) > 0) {
					if ($find == 0) {
						if ($contador == 50) {
							$fpdi->addPage();
							$fpdi->useTemplate($tplIdx, 0, 0);
							$fpdi->setFont('Arial', 'B', 8);
							//Fecha entrada y salida
							$fpdi->setXY(70, 29.5);
							$fpdi->write(10, $fechaInicio);
							$fpdi->setXY(160, 29.5);
							$fpdi->write(10, $fechaFin);
							$fpdi->setFont('Arial', '', 8);
							//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
							$find = 1;
							$Y = 48;
							$contador = 0;
						}
						$fpdi->setXY(20, $Y);
						$fpdi->write(10, $docente->clave);
						$fpdi->setXY(35, $Y);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $docente->nombre));
						$find = 1;
						$contador++;
					}
					//aqui aumentamos el dia ya que si tiene hora laboral
					$total++;
					foreach ($horarios as $horario) {
						$valor = Asistencias::getAsistenciaPersonal($horario->id_asignacion_horario, $docente->id, $fechaTomada);
						//vemos si cuenta con regitro
						if (count($valor)) {
							if ($valor[0]['id_estado'] == 1) {
								//retardo
								$retardos++;
							} else if ($valor[0]['id_estado'] == 2) {
								//A tiempo
							} else {
								$incidencia++;
							}
							if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
								//mandamos algo de falta ya que no cumplio con el chequeo
								$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
								$faltas += $valor2;
							} else {
								//calculamos horas asignadas
								$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
								//calculamos las horas trabajadas
								$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $valor[0]['hora_salida']);
								$horasTrabajadas += round($valor);
							}
						} else {

							$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
							$horasTrabajadas += 0;
							$faltas += $valor2;
						}
					}

				}

			}
			if ($find == 1) {
				//HACEMOS LOS SUBTOTALES GENERADOS
				$fpdi->setFont('Arial', '', 8);
				$fpdi->setXY(125, $Y);
				$fpdi->Write(10, $faltas);
				$totalFaltas += $faltas;
				$fpdi->setXY(110, $Y);
				$fpdi->write(10, $retardos);
				$totalRetardos += $retardos;
				$fpdi->setXY(145, $Y);
				$fpdi->write(10, $incidencia);
				$totalIncidencias += $incidencia;
				$subretardos = 0;
				if ($retardos > 2) {
					$subretardos = $retardos / 3;
				}
				$fpdi->setXY(165, $Y);
				$fpdi->Write(10, $horasTrabajadas);
				$totalHorasTrabajadas += $horasTrabajadas;
				$fpdi->setXY(185, $Y);
				$fpdi->Write(10, round($horasTrabajadas - $subretardos));
				$totalHorasPagadas += ($horasTrabajadas - $subretardos);
				$Y += 4;
			}
			/*if ($find == 1) {
				//HACEMOS LOS SUBTOTALES GENERADOS
				$fpdi->setFont('Arial', '', 8);
				$fpdi->setXY(50, 231);
				$fpdi->Write(10, $faltas);
				$fpdi->setXY(100, 231);
				$fpdi->write(10, $retardos);
				$subretardos = 0;
				if ($retardos > 2) {
					$subretardos = $retardos / 3;
				}
				$fpdi->setXY(175, 231);
				$fpdi->Write(10, $horasTrabajadas);
				$fpdi->setXY(175, 242);
				$fpdi->Write(10, $horasTrabajadas - $subretardos);
			}*/
		}


		$fpdi->setFont('Arial', 'B', 9);
		$Y += 5;
		$fpdi->setXY(80, $Y);
		$fpdi->Write(10, "TOTALES:");
		$fpdi->setXY(125, $Y);
		$fpdi->Write(10, $totalFaltas);
		$fpdi->setXY(110, $Y);
		$fpdi->write(10, $totalRetardos);
		$fpdi->setXY(145, $Y);
		$fpdi->write(10, $totalIncidencias);
		$fpdi->setXY(165, $Y);
		$fpdi->Write(10, $totalHorasTrabajadas);
		$fpdi->setXY(185, $Y);
		$fpdi->Write(10, round($totalHorasPagadas));
		$fpdi->setTitle("Concentrado Docente");
		$fpdi->Output('Concentrado Docente.pdf','I');
	}

	/**
	 * Obtiene el dia conforme a la base de datos
	 * @param $dia
	 * @return int
	 */
	public static function getDiaBD($dia) {
		/**
		 * La documentacion oficial en el formato date
		 * toma como 1 el primer dia de la semana a partir de lunes
		 * 1.- lunes
		 * 2.- martes
		 * ...etc
		 */
		$numero = 0;
		switch ($dia) {
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

	/**
	 * Obtiene el nombre del día
	 * @param $dia
	 * @return int|string
	 */
	public static function getNombreDia($dia) {
		$numero = 0;
		switch ($dia) {
			case 1:
				//lunes
				$numero = "LUNES";
				break;
			case 2:
				//martes
				$numero = "MARTES";
				break;
			case 3:
				//miercoles
				$numero = "MIERCOLES";
				break;
			case 4:
				//jueves
				$numero = "JUEVES";
				break;
			case 5:
				//viernes
				$numero = "VIERNES";
				break;
			case 6:
				//sabado
				$numero = "SABADO";
				break;
			case 7:
				//domingo
				$numero = "DOMINGO";
				break;
			default:
				$numero = 0;;
				break;
		}
		return $numero;
	}

	/**
	 * Metodo para hacer saltos de linea en la creación de pdfs
	 * @param $i
	 * @return int
	 */
	public static function saltos($i) {
		$valor = 0;
		switch ($i) {
			case 9:
				$valor = 3;
				break;
			case 15:
				$valor = 4;
				break;
			case 23:
				$valor = 3;
				break;
			case 36:
				$valor = 3;
				break;
			case 44:
				$valor = 3;
				break;
			case 56:
				$valor = 3;
				break;
			case 62:
				$valor = 3;
				break;
			case 76:
				$valor = 3;
				break;
			case 87:
				$valor = 3;
				break;
			case 103:
				$valor = 3;
				break;
			case 113:
				$valor = 3;
				break;
			default:
				$valor = 4.5;
		}
		return $valor;
	}

	/**
	 * Metodo para hacer saltos de linea en la creación de pdfs
	 * @param $i
	 * @return int
	 */
	public static function saltos2($i) {
		$valor = 0;
		switch ($i) {
			case 8:
				$valor = 4;
				break;
			case 4:
				$valor = 5;
				break;
			case 12:
				$valor = 5;
				break;
			default:
				$valor = 6;
		}
		return $valor;
	}

	/**
	 * Metodo para calcular la diferecion en minutos
	 * @param $fecha
	 * @param $horaInicio
	 * @param $horaFin
	 * @return float
	 */
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


	/**
	 * REPORTE INDIVIDUAL
	 */

	/**
	 * Genera el reporte individual dependiendo de si es administrativo o es docente
	 * @param $idEmpleado
	 * @param $fechaInicio
	 * @param $fechaFin
	 */
	public static function generaIndividual($idEmpleado, $fechaInicio, $fechaFin) {
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($idEmpleado);
		switch ($empleado->getIdPuesto()) {
			case 9:
				//DOCENTE
				self::individualDocente($fechaInicio, $fechaFin, $empleado);
				break;
			default:
				//Cualquier otro tipo de usuario
				self::individualAdmon($fechaInicio, $fechaFin, $empleado);
				break;
		}
	}

	/**
	 * Genera el reporte individual en formato pdf para el administrativo
	 * @param $fechaInicio
	 * @param $fechaFin
	 * @param Empleados $empleado
	 */
	public static function individualAdmon($fechaInicio, $fechaFin, Empleados $empleado) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/admon.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		$fpdi->SetTitle('Reporte Administrativo');
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));
		$fpdi->addPage();
		$fpdi->useTemplate($tplIdx, 0, 0);
		$fpdi->setFont('Arial', 'B', 8);
		//nombre
		$fpdi->setXY(62, 25.5);
		$fpdi->write(10, iconv('UTF-8', 'windows-1252', $empleado->getNombre() . " " . $empleado->getApellidos()));
		//Fecha entrada y salida
		$fpdi->setXY(68, 28.5);
		$fpdi->write(10, $fechaInicio);
		$fpdi->setXY(65, 32);
		$fpdi->write(10, $fechaFin);
		//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
		$X = 27;
		$Y = 44;
		//variables
		$restardos = 0;
		$asistencias = 0;
		$subtotal = 0;
		$total = 0;
		$faltas = 0;
		$fpdi->setFont('Arial', '', 8);
		for ($i = 0; $i <= $dias; $i++) {
			//fecha a consultar
			$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
			$weekday = date('N', strtotime($fechaTomada));
			$diaConsultar = self::getDiaBD($weekday);
			//checamos primero si el usuario tiene hora asignada
			$horarios = AsignacionHorario::getHorarioPersonalDia($diaConsultar, $empleado->getId());
			if (count($horarios) > 0) {
				$total++;
				$fpdi->setXY($X, $Y);
				$fpdi->write(10, self::getNombreDia($weekday));
				$fpdi->setXY(50, $Y);
				$fpdi->write(10, date('d-m-Y', strtotime($fechaInicio . ' +' . $i . ' day')));
				foreach ($horarios as $horario) {
					//echo date_diff(strtotime($horario->hora_entrada),strtotime($horario->hora_salida));
					$entradaint = intval($horario->hora_entrada);
					$salidaint = intval($horario->hora_salida);
					if (($salidaint - $entradaint) > 2) {
						$fpdi->setXY(80, $Y);
						//$fpdi->write(10, $horario->hora_entrada);
						//VERIFICAMOS EL CHEQUEO ANTERIOR
						$valor = Asistencias::getAsistenciaPersonal($horario->idA, $empleado->getId(), $fechaTomada);
						if (count($valor) > 0) {
							$fpdi->write(10, $valor[0]['hora_llegada']);
							$fpdi->setXY(100, $Y);
							if ($valor[0]['id_estado'] == 1) {
								$restardos += 1;
								$fpdi->write(10, "RETARDO");
							} else if ($valor[0]['id_estado'] == 2) {
								$fpdi->write(10, "A TIEMPO");
							} else {
								$fpdi->write(10, "POR INCIDENCIA");
							}
							if (!empty($valor[0]['hora_salida'])){
								$fpdi->setXY(140, $Y);
								$fpdi->write(10, self::generaHorarioRandom($horario->hora_salida));
							}
							$fpdi->setXY(165, $Y);
							if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
								//mandamos algo de falta ya que no cumplio con el chequeo
								$fpdi->write(10, 'FALTA');
								$faltas++;
							} else {
								$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $horario->hora_salida);
								if ($valor >= 7.70) {
									$asistencias += 1;
									$fpdi->write(10, 'ASISTENCIA');
								} else {
									//$restardos += 1;
									$asistencias += 1;
									$fpdi->write(10, 'RETARDO');
								}
							}
						} else {
							$fpdi->setXY(165, $Y);
							$fpdi->write(10, 'NO CHECO');
							$faltas++;
						}


					}

				}

				$Y += self::saltos2($i);
			}
		}
		//HACEMOS LOS SUBTOTALES GENERADOS
		$fpdi->setXY(50, 238);
		$fpdi->write(10, $faltas);
		$fpdi->setXY(100, 238);
		$fpdi->write(10, $restardos);
		$subretardos = 0;
		if ($restardos > 2) {
			$subretardos = $restardos / 3;
		}

		$fpdi->setXY(165, 238);
		$fpdi->write(10, $asistencias);
		$fpdi->setXY(165, 249);
		$fpdi->write(10, $asistencias - round($subretardos));
		$fpdi->setTitle($empleado->getNombre() . " " . $empleado->getApellidos());
		$fpdi->Output($empleado->getNombre() . " " . $empleado->getApellidos().'.pdf','I');
	}

	/**
	 * Genera el reporte individual en formato pdf para el docente
	 * @param $fechaInicio
	 * @param $fechaFin
	 * @param Empleados $docente
	 */
	public static function individualDocente($fechaInicio, $fechaFin, Empleados $docente) {
		$fpdi = new \fpdi\FPDI('P', 'mm', 'A4');
		$link = "components/pdf/docente-chequeo.pdf";
		$pageCount = $fpdi->setSourceFile($link);
		$tplIdx = $fpdi->importPage(1, '/MediaBox');
		$size = $fpdi->getTemplateSize($tplIdx);
		//los dias a tomar
		$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
		$dias = floor($diferencia / (60 * 60 * 24));
		//Obtenermos primero los docentes
		$retardos = 0;
		$horasTrabajadas = 0;
		$horasAsignadas = 0;
		$subtotal = 0;
		$total = 0;
		$faltas = 0;
		$find = 0;
		$X = 27;
		$Y = 42;
		$jj = 0;
		for ($i = 0; $i <= $dias; $i++) {
			//CHECAMOS DIA POR DIA LA ASIGNACION DE HORAS QUE TIENEN
			//fecha a consultar
			$fechaTomada = date('Y-m-d', strtotime($fechaInicio . ' +' . $i . ' day'));
			$weekday = date('N', strtotime($fechaTomada));
			$diaConsultar = self::getDiaBD($weekday);
			$horarios = Horarios::getHorariClase($docente->getId(), $diaConsultar);
			//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
			if (count($horarios) > 0) {
				if ($find == 0) {
					$fpdi->addPage();
					$fpdi->useTemplate($tplIdx, 0, 0);
					$fpdi->setFont('Arial', 'B', 8);
					//nombre
					$fpdi->setXY(62, 25.5);
					$fpdi->write(10, iconv('UTF-8', 'windows-1252', $docente->getNombre() . " " . $docente->getApellidos()));
					//Fecha entrada y salida
					$fpdi->setXY(68, 28.5);
					$fpdi->write(10, $fechaInicio);
					$fpdi->setXY(65, 32);
					$fpdi->write(10, $fechaFin);
					//TENIEDO LOS DIAS CHECAMOS DIA POR DIA TRANSCURRIDO
					$find = 1;
				}
				$fpdi->setFont('Arial', '', 8);
				$fpdi->setXY($X, $Y);
				$fpdi->write(10, self::getNombreDia($weekday));
				$fpdi->setXY(53, $Y);
				$fpdi->write(10, date('d-m-Y', strtotime($fechaInicio . ' +' . $i . ' day')));
				//aqui aumentamos el dia ya que si tiene hora laboral
				$total++;
				foreach ($horarios as $horario) {
					if ($jj >= 31 && $jj<=31) {
						$fpdi->addPage();
						$fpdi->useTemplate($tplIdx, 0, 0);
						$fpdi->setFont('Arial', 'B', 8);
						//nombre
						$fpdi->setXY(62, 25.5);
						$fpdi->write(10, iconv('UTF-8', 'windows-1252', $docente->getNombre() . " " . $docente->getApellidos()));
						//Fecha entrada y salida
						$fpdi->setXY(68, 28.5);
						$fpdi->write(10, $fechaInicio);
						$fpdi->setXY(65, 32);
						$fpdi->write(10, $fechaFin);
						$Y = 42;
						$jj = 0;
					}
					$valor = Asistencias::getAsistenciaPersonal($horario->id_asignacion_horario, $docente->getId(), $fechaTomada);
					//vemos si cuenta con regitro
					if (count($valor)) {
						$fpdi->setXY(78, $Y);
						$fpdi->write(10, $valor[0]['hora_llegada']);
						$fpdi->setXY(125, $Y);
						if ($valor[0]['id_estado'] == 1) {
							$fpdi->write(10, "RETARDO");
							$retardos++;
						} else if ($valor[0]['id_estado'] == 2) {
							$fpdi->write(10, "A TIEMPO");
						} else {
							$fpdi->setFont('Arial', '', 6);
							$fpdi->setXY(124, $Y);
							$fpdi->write(10, "POR INCIDENCIA");
							$fpdi->setFont('Arial', '', 8);
						}
						$fpdi->setXY(100, $Y);
						$fpdi->write(10, $valor[0]['hora_salida']);
						$fpdi->setXY(165, $Y);
						if (empty($valor[0]['hora_salida']) || empty($valor[0]['hora_llegada'])) {
							$fpdi->setXY(176, $Y);
							//mandamos algo de falta ya que no cumplio con el chequeo
							$fpdi->write(10, 'FALTA');
							$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
							$fpdi->setXY(153, $Y);
							$fpdi->write(10, $valor2);
							$faltas += $valor2;
						} else {
							//calculamos horas asignadas
							$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
							//$horasAsignadas+= $valor2;
							$fpdi->setXY(153, $Y);
							$fpdi->write(10, $valor2);
							//calculamos las horas trabajadas
							$valor = self::calcularHoras($valor[0]['fecha'], $valor[0]['hora_llegada'], $valor[0]['hora_salida']);
							$horasTrabajadas += round($valor);
							$fpdi->setXY(180, $Y);
							$fpdi->write(10, round($valor));
						}
						$horasAsignadas+= $valor2;
					} else {
						$fpdi->setXY(80, $Y);
						$fpdi->write(10, "-----");
						$fpdi->setXY(105, $Y);
						$fpdi->write(10, "-----");
						$fpdi->setXY(127, $Y);
						$fpdi->write(10, "-----");
						$fpdi->setXY(153, $Y);
						$valor2 = self::calcularHoras($fechaTomada, $horario->hora_entrada, $horario->hora_salida);
						$horasAsignadas+= $valor2;
						$fpdi->write(10, $valor2);
						$fpdi->setXY(180, $Y);
						$fpdi->write(10, 0);
						$horasTrabajadas += 0;
						$faltas += $valor2;
					}
					$jj++;
					$Y += self::saltos2($jj);
				}

				//$Y += self::saltos2($i);
			}

		}
		if ($find == 1) {
			//HACEMOS LOS SUBTOTALES GENERADOS
			$fpdi->setFont('Arial', '', 8);
			$fpdi->setXY(50, 231);
			$fpdi->Write(10, $faltas);
			$fpdi->setXY(100, 231);
			$fpdi->write(10, $retardos);
			$subretardos = 0;
			if ($retardos > 2) {
				$subretardos = $retardos / 3;
			}
			$fpdi->setXY(150,226);
			$fpdi->Write(10, $horasAsignadas);
			$fpdi->setXY(175, 231);
			$fpdi->Write(10, $horasTrabajadas);
			$fpdi->setXY(175, 242);
			$fpdi->Write(10, $horasTrabajadas - $subretardos);
		}
		$fpdi->setTitle($docente->getNombre() . " " . $docente->getApellidos());
		$fpdi->Output($docente->getNombre() . " " . $docente->getApellidos().".pdf","I");
	}

	public static function generaHorarioRandom($hora){
		$horario = null;
		$minutos = rand(1,9);
		$segundos = rand(0,59);
		$fechaActual = date("Y-m-d");
		$horass = date("Y-m-d H:i:s",strtotime($fechaActual." ".$hora));
		$horario = strtotime("+".$minutos." minute",strtotime($horass));
		//$horario = strtotime("-".$segundos . " second",strtotime($horario));
		$horarios  = date("H:i:s",$horario);
		$horario2 = strtotime("+".$segundos . " second",strtotime($horarios));
		$horarios3  = date("H:i:s",$horario2);
		/*$minutos = rand(0,59);
		$segundos = rand(0,59);
		$fechaActual = date("Y-m-d");
		$hora = date("Y-m-d H:i:s",strtotime($fechaActual." ".$hora));
		$horario = date("H:i:s", strtotime($fechaActual." ".$hora.":".$minutos.":".$segundos));*/
		return $horarios3;
	}
}