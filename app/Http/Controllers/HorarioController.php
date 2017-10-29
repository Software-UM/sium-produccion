<?php

namespace App\Http\Controllers;

use App\Bloqueo;
use App\Clases\AsignacionClase;
use App\Clases\AsignacionClaseAudition;
use App\Clases\AsignacionHorario;
use App\Clases\Asistencias;
use App\Clases\Carrera;
use App\Clases\Departamentos;
use App\Clases\Empleados;
use App\Clases\Grupo;
use App\Clases\Horarios;
use App\Clases\Incidencias;
use App\Clases\Materia;
use App\Clases\reportes\ReporteAsignaciones;
use App\Clases\Utilerias;
use App\Edificios;
use App\Empleado;
use App\FechasPosgrados;
use App\Parametros;
use App\Salones;
use App\TipoHorarios;
use App\Plantel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Session;
use Validator;

class HorarioController extends Controller {
	public function inicio() {
		$horario = new Horarios();
		$empleado = Empleado::find(\Auth::user()->empleado_id);
		$plantel = Plantel::find($empleado->cct_plantel);
		return view("PersonalViews.Horario.horario", ['horarios' => $horario->getHorarios(), 'plantel'=>$plantel]);
	}

	public function add() {
		$tipos = TipoHorarios::all();
		return view("PersonalViews.Horario.formHorario", ['tipos' => $tipos]);
	}

	public function agregar(Request $request) {
		$validar = Validator::make($request->all(), [
			'entrada' => 'required',
			'salida' => 'required',
			'turno' => 'required',
			'checkboxvar' => 'required',
			'tipo' => 'required'
		]);
		if ($validar->fails()) {
			return redirect()->back()->withErrors($validar->errors());
		} else {
			//se guarda los datos
			$dias = $request->checkboxvar;
			$horario = Horarios::withData(0, $request->entrada, $request->salida, 0, $request->tipo);
			$response = [];
			for ($i = 0; $i < count($dias); $i++) {
				$horario->setDia($dias[$i]);
				$response = $horario->insertHorario($horario);
				if (isset($response['error']))
					return redirect()->back()->with('mensaje', $response['error']);
			}
			return redirect()->action("HorarioController@inicio")->with('mensaje', $response['success']);
		}
	}

	public function agregarSingle(Request $request) {
		$validar = Validator::make($request->all(), [
			'entrada' => 'required',
			'salida' => 'required',
			'turno' => 'required',
			'checkboxvar' => 'required',
			'tipo' => 'required',
			'idEmpleado' => 'required'
		]);
		if ($validar->fails()) {
			return $validar->errors();
		} else {
			//se guarda los datos
			$dias = $request->checkboxvar;
			$horario = Horarios::withData(0, $request->entrada, $request->salida, 0, $request->tipo);
			$response = [];
			for ($i = 0; $i < count($dias); $i++) {
				$horario->setDia($dias[$i]);
				$response = $horario->insertHorario($horario);
				if (isset($response['error'])) {
					return $response;
				} else {
					$idAsignacion = $this->asignarHora($response['idHorario'], $request->idEmpleado);
				}
			}
			return ['error' => 'se asignó correctamente el horario'];
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function modificar(Request $request) {
		$horarios = new Horarios();
		$tipos = TipoHorarios::all();
		print_r($horarios->getHorarioIdTipo($request->id)[0]['hora_salida']);
		return view('PersonalViews.Horario.formModHorario', ['horarios' => $horarios->getHorarioIdTipo($request->id),
			'tipos' => $tipos]);
	}

	//ASIGNACION GRUPAL
	public function grupo() {
		$asignacionClase = new AsignacionClase();
		$empleado = Empleado::find(\Auth::user()->empleado_id);
		$edificios = Edificios::where("cct_plantel", $empleado->cct_plantel)->get();
		$plantel = Plantel::find($empleado->cct_plantel);
		$bloqueo = Bloqueo::first();
		return view('PersonalViews.Horario.horarioGrupal', ['grupos' => $asignacionClase->getClaseGrupo($empleado->cct_plantel),
			'gruposSemi' => $asignacionClase->getClaseGrupoSemi($empleado->cct_plantel), "response" => $edificios, 'bloqueo' => $bloqueo,
			'plantel' => $plantel]);
	}

	/**
	 * Método que valida si el grupo, carrera y ciclo ya están asignados,
	 * en el caso contrario se visualiza la asignación
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function asignarGrupo(Request $request) {
		$validar = Validator::make($request->all(), [
			'modalidad' => 'required',
			'carrera' => 'required',
			'grupo' => 'required'
		]);

		if ($validar->fails()) {
			return redirect()->back()->withErrors($validar->errors());
		} else {
			$idGrupo = $request->grupo;
			$idCarrera = $request->carrera;
			$idCiclo = $request->ciclo;
			$idModalidad = $request->modalidad;
			//se hace la segunda validación para saber si no hay ya un grupo con asignacion
			$asignacion = new AsignacionClase();
			$response = $asignacion->findGrupoAsigando($idGrupo, $idCarrera, $idCiclo);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			}
			if (count($response) > 0) {
				return redirect()->back()->with('mensaje', 'Ya se encuentra una asignacion para este grupo');
			} else {
				//obtenemos los edificios
				$empleado = Empleado::find(\Auth::user()->empleado_id);
				$edificios = Edificios::where("cct_plantel", $empleado->cct_plantel)->get();
				return redirect()->action("HorarioController@addGrupo")->with('data',
					['grupo' => $idGrupo, 'carrera' => $idCarrera, 'ciclo' => $idCiclo, 'edificios' => $edificios, 'modalidad' => $idModalidad]);
			}
		}
	}


	public function addGrupo() {
		if (Session::has('data')) {
			$response = (object)Session::get('data');
			$grupos = new Grupo();
			$carreras = new Carrera();
			$empleados = new Empleados();
			$materias = new Materia();
			$params = ['grupo' => $grupos->getGrupoM($response->grupo),
				'carrera' => $carreras->getCarrera($response->carrera),
				'empleados' => $empleados->getDocentes($carreras->getCctPlantel()), 'materia' => $materias->getMateriaPlan($grupos->getGrado(), $response->carrera, $response->modalidad),
				'ciclo' => $response->ciclo, 'edificios' => $response->edificios];

			return redirect()->back()->with('data', $params);

		} else {
			return redirect()->action('CarreraController@seleccionar')->with('mensaje', 'No hay datos para asignar');
		}
	}

	public function salones(Request $request) {
		//return $request->id;
		$salones = Salones::where("id_edificio", $request->id)->get();
		return $salones;
	}

	//FALTA ACTUALIZAR AL SERVER
	public function updateSalones(Request $request) {
		$idSalon = $request->idSalon;
		$idSalonNuevo = $request->idSalonNuevo;
		$ciclo = $request->ciclo;
		$idGrupo = $request->idGrupo;
		$grupos = new Grupo();
		$grupos->getGrupoM($request->idGrupo);
		$idCarrera = $request->idCarrera;
		$dia = $request->dia;
		$asignacionClase = new AsignacionClase();
		if ($dia == 1) {
			return $asignacionClase->updateSalon($ciclo, $idSalon, $idGrupo, $idSalonNuevo, $idCarrera, $dia);
		} else {
			$salonesClase = $asignacionClase->getAsignacionDia($ciclo, $idSalon, $grupos->getIdModalidad(), $dia, $idCarrera, $idGrupo);
			$response = ["vacio" => "vacio"];
			foreach ($salonesClase as $salones) {
				$response = $asignacionClase->updateSalonSingle($salones->id, $idSalonNuevo);
			}

			return $response;
		}

	}

	public function validar(Request $request) {
		//return $request->id;
		$asignacionClase = new AsignacionClase();
		$grupos = new Grupo();
		$grupos->getGrupoM($request->idGrupo);
		$dia = $request->modalidad;
		$respuesta = $asignacionClase->buscarSalon($request->ciclo, $request->idSalon, $grupos->getIdModalidad(), $dia);
		return $respuesta;
	}

	public function validador(Request $request) {
		//guardamos los request en variables para su manejo
		$idEmpleado = $request->idEmpleado;
		$idGrupo = $request->idGrupo;
		$idMateria = $request->idMateria;
		$hora = $request->hora;
		$dia = $request->dia;
		$horario = $request->horario;
		$ciclo = $request->ciclo;
		$idCarrera = $request->idCarrera;
		$idSalon = $request->salon;
		//verificamos los posibles
		$arrayHoras = [
			0 => ['7:00', '8:00'],
			1 => ['8:00', '9:00'],
			2 => ['9:00', '10:00'],
			3 => ['10:00', '11:00'],
			4 => ['11:00', '12:00'],
			5 => ['12:00', '13:00'],
			6 => ['13:00', '14:00'],
			7 => ['14:00', '15:00'],
		];
		$asignacionHorario = new AsignacionHorario();
		$responseFinal = null;
		$idUser = \Auth::user()->empleado_id;
		if ($horario > 0) {
			for ($i = 0; $i <= $horario; $i++) {
				$horaEntrada = $arrayHoras[$hora + $i][0];
				$horaSalida = $arrayHoras[$hora + $i][1];
				$response = $asignacionHorario->getAsignacion($idEmpleado, $horaEntrada, $horaSalida, $dia, $ciclo);
				//$responseFinal =  $response;
				if (count($response) > 0) {
					return ['error' => "Ya se encuentra una asignación a esa hora para el docente \n" .
						"Grado y Grupo: " . $response[0]['grado'] . " " . $response[0]['grupo']
						. " Carrera: " . $response[0]['nombre']];
				} else {
					$idAsignacionHorario = $this->guardarHorario($horaEntrada, $horaSalida, $dia, 1, $idEmpleado);
					if ($idAsignacionHorario > 0) {
						$responseFinal = $this->asignarClase($idGrupo, $idAsignacionHorario, $idMateria, $ciclo, $idCarrera, $idSalon);
						//agregamos la asignacion audit
						$asignacionAudit = new AsignacionClaseAudition(0, $horaEntrada, $horaSalida, $dia, $ciclo, $idGrupo, $idCarrera, $idMateria, $idEmpleado, $idUser, 1);
						$responseFinal = $asignacionAudit->insert();
					} else
						$responseFinal = ['error' => 'Error al asignar la clase: contacte a sistemas'];
				}
			}
		} else {
			$horaEntrada = $arrayHoras[$hora][0];
			$horaSalida = $arrayHoras[$hora][1];
			$response = $asignacionHorario->getAsignacion($idEmpleado, $horaEntrada, $horaSalida, $dia, $ciclo);
			//$responseFinal =  $response;
			if (count($response) > 0) {
				return ['error' => "Ya se encuentra una asignación a esa hora para el docente \n" .
					"Grado y Grupo: " . $response[0]['grado'] . " " . $response[0]['grupo']
					. " Carrera: " . $response[0]['nombre']];
			} else {
				$idAsignacionHorario = $this->guardarHorario($horaEntrada, $horaSalida, $dia, 1, $idEmpleado);
				if ($idAsignacionHorario > 0) {
					$responseFinal = $this->asignarClase($idGrupo, $idAsignacionHorario, $idMateria, $ciclo, $idCarrera, $idSalon);
					//agregamos la asignacion audit
					$asignacionAudit = new AsignacionClaseAudition(0, $horaEntrada, $horaSalida, $dia, $ciclo, $idGrupo, $idCarrera, $idMateria, $idEmpleado, $idUser, 1);
					$responseFinal = $asignacionAudit->insert();
				} else
					$responseFinal = ['error' => 'Error al asignar la clase: contacte a sistemas'];
			}
		}
		return $responseFinal;

	}

	/**
	 * @param Request $request
	 * @return array|null
	 */
	public function validadorPosgrado(Request $request) {
		$idEmpleado = $request->idEmpleado;
		$idGrupo = $request->idGrupo;
		$idMateria = $request->idMateria;
		$horaEntrada = $request->horaEntrada;
		$dia = $request->dia;
		$horaSalida = $request->horaSalida;
		$ciclo = $request->ciclo;
		$idCarrera = $request->idCarrera;
		$idSalon = $request->salon;
		$asignacionHorario = new AsignacionHorario();
		$responseFinal = null;
		$response = $asignacionHorario->getAsignacion($idEmpleado, $horaEntrada, $horaSalida, $dia, $ciclo);
		//$responseFinal =  $response;
		$fechasPosgrado = new FechasPosgrados();
		if (count($response) > 0) {
			/*return ['error' => "Ya se encuentra una asignacion a esa hora para el docente ::" .
				"Grado y Grupo: " . $response[0]['grado'] . " " . $response[0]['grupo']
				. " Carrera: " . $response[0]['nombre']];*/
			//se agrega la nueva fecha
			$fechasPosgrado->fecha = $request->fechaClase;
			$fechasPosgrado->id_asignacion_clase = $response[0]['id'];
			$fechasPosgrado->save();
			$responseFinal = ['success' => 'Se asigno correctamente la fecha', 'id_fecha' => $fechasPosgrado->id];
		} else {

			$idAsignacionHorario = $this->guardarHorario($horaEntrada, $horaSalida, $dia, 1, $idEmpleado);
			if ($idAsignacionHorario > 0) {
				$responseFinal = $this->asignarClase($idGrupo, $idAsignacionHorario, $idMateria, $ciclo, $idCarrera, $idSalon);
				$fechasPosgrado->fecha = $request->fechaClase;
				$fechasPosgrado->id_asignacion_clase = $responseFinal['id_clase'];
				$fechasPosgrado->save();
				$responseFinal['id_fecha'] = $fechasPosgrado->id;
			} else
				$responseFinal = ['error' => 'Error al asignar la clase: contacte a sistemas'];
		}

		return $responseFinal;
	}

	/**
	 * @param $horaEntrada
	 * @param $horaSalida
	 * @param $dia
	 * @param $tipo
	 * @param $idEmpleado
	 * @return int
	 */
	function guardarHorario($horaEntrada, $horaSalida, $dia, $tipo, $idEmpleado) {
		$horario = Horarios::withData(0, $horaEntrada, $horaSalida, $dia, $tipo);
		$response = $horario->insertHorario($horario);

		if (isset($response['error'])) {
			return 0;
		} else {
			$idAsignacion = $this->asignarHora($response['idHorario'], $idEmpleado);
			return $idAsignacion;
		}
	}

	/**
	 * @param $idHorario
	 * @param $idEmpleado
	 * @return int
	 */
	function asignarHora($idHorario, $idEmpleado) {
		$asignacionHora = AsignacionHorario::withData(0, date("Y-m-d H:i:s"), $idHorario, $idEmpleado);
		$response = $asignacionHora->insertAsignacionHorario($asignacionHora);
		if (isset($response['error'])) {
			return 0;
		} else {
			return $response['idAsignacion'];
		}
	}

	/**
	 * @param $idGrupo
	 * @param $idAsignacion
	 * @param $claveMateria
	 * @param $idCiclo
	 * @param $idCarrera
	 * @return array
	 */
	function asignarClase($idGrupo, $idAsignacion, $claveMateria, $idCiclo, $idCarrera, $idSalon) {
		$asignacionClase = AsignacionClase::withData(0, $idGrupo, $idAsignacion, $claveMateria, $idCiclo, $idCarrera, $idSalon);
		$response = $asignacionClase->insertClaseDocente($asignacionClase);
		return $response;
	}

	/**
	 * Obtiene la lista actual de pogrado
	 * @param Request $request
	 */
	function getAsignacionp(Request $request) {

	}

	function reporte(Request $request) {
		return ReporteAsignaciones::imprimeGrupo($request);
	}

	public function modificarAsignacion(Request $request) {
		$grupos = new Grupo();
		$grupos->getGrupoM($request->idGrupo);
		$carreras = new Carrera();
		$carreras->getCarrera($request->idCarrera);
		$idSalon = $request->salon;
		$empleados = new Empleados();
		$materias = new Materia();
		$horario = new Horarios();
		$cct = $carreras->getCctPlantel();
		$arrayHoras = [
			0 => ['7:00', '8:00'],
			1 => ['8:00', '9:00'],
			2 => ['9:00', '10:00'],
			3 => ['10:00', '11:00'],
			4 => ['11:00', '12:00'],
			5 => ['12:00', '13:00'],
			6 => ['13:00', '14:00'],
			7 => ['14:00', '15:00']
		];
		$array = [];
		for ($i = 0; $i < count($arrayHoras); $i++) {
			array_push($array, $horario->getHorarioAsignacion($request->idCiclo, $arrayHoras[$i][0], $carreras->getId(), $grupos->getId()));
		}
		$params = ['grupo' => $grupos,
			'carrera' => $carreras,
			'empleados' => $empleados->getDocentes($cct), 'materia' => $materias->getMateriaPlan($grupos->getGrado(), $request->idCarrera, $grupos->getIdModalidad()),
			'ciclo' => $request->idCiclo, 'arrayHoras' => $array, 'idSalon' => $idSalon];
		return view('PersonalViews.Horario.selectGrupoMod', $params);
	}

	public function saveModAsignacion(Request $request) {
		$idCiclo = $request->ciclo;
		$idEmpleado = $request->idEmpleado;
		$idGrupo = $request->idGrupo;
	}

	public function dropAsignacion(Request $request) {
		//Audit
		//
		$asignacionC = new AsignacionClase();
		$clase = $asignacionC->getClase($request->idClase);
		$horariosA = new AsignacionHorario();
		$idUser = \Auth::user()->empleado_id;
		$horario = $horariosA->getAsignacionHorario($clase->id_asignacion_horario);
		$horarioSingle = Horarios::getCargaAcademicaSingle($horario->id_empleado, $horario->id,$clase->id_ciclos);
		$audit = new AsignacionClaseAudition(0, $horarioSingle->hora_entrada,
			$horarioSingle->hora_salida, $horarioSingle->dia, $clase->id_ciclos,
			$horarioSingle->id_grupos, $horarioSingle->id_carreras,
			$horarioSingle->clave_materias, $horario->id_empleado, $idUser, 3);
		$response = $audit->insert();
		$responseClase = $asignacionC->deleteClase($request->idClase);
		if (isset($responseClase['error'])) {
			return $responseClase;
		} else {
			return $responseClase;
		}
	}

	public function buscarFechas(Request $request) {
		$idCiclo = $request->ciclo;
		$idEmpleado = $request->idEmpleado;
		$idGrupo = $request->idGrupo;
		$idMateria = $request->idMateria;
		$idCarrera = $request->idCarrera;

		$asignacionClase = new AsignacionClase();
		$lista = $asignacionClase->obtenerFechas($idEmpleado, $idCiclo, $idGrupo, $idCarrera, $idMateria);
		if (isset($lista["error"])) {
			return $lista["error"];
		} else {
			return $lista;
		}
	}

	/**
	 * Eliminar fecha de asignacion posgrado
	 */
	public function deleteFecha(Request $request) {
		try {
			$fechaPosgrado = FechasPosgrados::find($request->idFecha);
			$fechaPosgrado->delete();
			return ["success" => "Se elimino la fecha seleccionada"];
		} catch (QueryException $e) {
			return ["error" => "Error al eliminar las fecha" . $e->getMessage()];
		}
	}

	/**
	 * * Metodo para eliminar la asignacion de horario individual
	 * @param Request $request
	 */
	public function dropSingle(Request $request) {
		$asignacionHora = new AsignacionHorario();
		$responseHora = $asignacionHora->deleteAsignacionHorario($request->id);
		if (isset($responseHora['error'])) {
			return $responseHora;
		} else {
			return $responseHora;
		}
	}

	public function saveModA(Request $request) {
		$asigHorario = new AsignacionHorario();
		$idUser = \Auth::user()->empleado_id;
		return $asigHorario->updateAsignacionEmpleado($request->idAsignacionHora, $request->idEmpleado, $idUser);
	}

	/**
	 * TIPO HORARIO MODULO
	 */

	public function tipo() {
		$tipos = TipoHorarios::join('parametros', 'parametros.id', '=', 'id_parametros')
			->select("tipo_horario.*", "tiempo_antes", "tiempo_despues")->get();
		return view("PersonalViews.Horario.tipo", ['tipos' => $tipos]);
	}

	public function addTipo() {
		return view("PersonalViews.Horario.formTipo");
	}

	public function saveTipo(Request $request) {
		$validar = Validator::make($request->all(), [
			'descripcion' => 'required|unique:tipo_horario',
			'antes' => 'required|integer',
			'despues' => 'required|integer',
		]);
		if ($validar->fails()) {
			return redirect()->back()->withErrors($validar->errors());
		} else {
			try {
				$params = new Parametros();
				$params->tiempo_antes = $request->antes;
				$params->tiempo_despues = $request->despues;
				$params->save();
				$tipoHorario = new TipoHorarios();
				$tipoHorario->descripcion = $request->descripcion;
				$tipoHorario->id_parametros = $params->id;
				$tipoHorario->save();
				return redirect()->action("HorarioController@tipo")->with('mensaje',
					'Se guardo correctamento el tipo horario');
			} catch (QueryException $e) {
				return redirect()->back()->with('mensaje', "Error al guardar tipo horario: " . $e->getMessage());
			}

		}
	}

	public function modifyTipo(Request $request) {
		$tipos = TipoHorarios::join('parametros', 'parametros.id', '=', 'id_parametros')
			->select("tipo_horario.*", "tiempo_antes", "tiempo_despues", "parametros.id AS idParam")
			->where("tipo_horario.id", $request->id)->get();
		return view("PersonalViews.Horario.formModTipo", ['tipos' => $tipos[0]]);
	}

	public function saveModTipo(Request $request) {
		$validar = Validator::make($request->all(), [
			'descripcion' => 'required',
			'antes' => 'required|integer',
			'despues' => 'required|integer',
		]);
		if ($validar->fails()) {
			return redirect()->back()->withErrors($validar->errors());
		} else {
			try {
				$params = Parametros::find($request->idParam);
				$params->tiempo_antes = $request->antes;
				$params->tiempo_despues = $request->despues;
				$params->save();
				$tipoHorario = TipoHorarios::find($request->id);
				$tipoHorario->descripcion = $request->descripcion;
				$tipoHorario->id_parametros = $request->idParam;
				$tipoHorario->save();
				return redirect()->action("HorarioController@tipo")->with('mensaje',
					'Se Modifico correctamento el tipo horario');
			} catch (QueryException $e) {
				return redirect()->back()->with('mensaje', "Error al modificar tipo horario: " . $e->getMessage());
			}

		}
	}

	public function eliminarTipo(Request $request) {
		try {
			$tipoHorario = TipoHorarios::find($request->idTipo);
			$idParametro = $tipoHorario->id_parametros;
			$tipoHorario->delete();
			$params = Parametros::find($idParametro);
			$params->delete();
			return ['success' => 'Tipo Horario eliminada'];
		} catch (QueryException $e) {
			return ['error' => "Error al eliminar tipo horario: " . $e->getMessage()];
		}
	}

	/**
	 * ASIGNACIÓN HORARIO PERSONAL MODULO
	 */

	public function inicioAsignacion() {
		return view('PersonalViews.Horario.Asignacion.inicio');
	}

	public function asignacionAdd() {
		$departamentos = new Departamentos();
		$tipos = TipoHorarios::all();
		$empleado = Empleado::find(\Auth::user()->empleado_id);
		$plantel = Plantel::find($empleado->cct_plantel);
		$paramas = ['departamentos' => $departamentos->getDepartamentosWithoutDocentes(),
			'tipos' => $tipos, 'plantel' => $plantel];
		return view('PersonalViews.Horario.Asignacion.form', $paramas);
	}

	public function saveAsignacion(Request $request) {
		$validar = Validator::make($request->all(), [
			'departamento' => 'required',
			'tipo' => 'required',
			'empleados' => 'required',
		]);
		if ($validar->fails()) {
			return redirect()->back()->withErrors($validar->errors());
		} else {
			$response = [];
			foreach ($request->tipo as $tipo) {
				$hor = new Horarios();
				$horarios = $hor->getHorarioIdTipo($tipo);
				foreach ($horarios as $hor) {
					foreach ($request->empleados as $empleado) {
						$asignacionHora = AsignacionHorario::withData(0, date("Y-m-d H:i:s"), $hor->id, $empleado);
						$response = $asignacionHora->insertAsignacionHorario($asignacionHora);
					}
				}
			}
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else {
				//return redirect()->action("HorarioController@inicioAsignacion")->with('mensaje', $response['success']);
				return redirect()->action("HorarioController@asignacionAdd")->with('success', $response['success']);
			}
		}
	}

	public function incidencia() {
		//TODO agregar esto en la nueva version
        $idEmpleado = \Auth::user()->empleado_id;
        $empleado = new Empleados();
        $singleEmpleado = $empleado->getSingleEmpleado($idEmpleado);
        $plantel = Plantel::find($singleEmpleado->cct_plantel);
        $tipoAdmon = 9; //Administrativo
		return view('PersonalViews.Horario.Asignacion.incidencia',
            ['empleados' => Empleados::getAdmons($singleEmpleado->cct_plantel, $tipoAdmon), 'plantel' => $plantel]);
	}

	public function saveIncidencia(Request $request) {
		$valor = intval($request->tipo);
		//instanciamos las clases
		$incidencia = new Incidencias();
		switch ($valor) {
			case 1:
				$validar = Validator::make($request->all(), [
					'empleado' => 'required',
					'entrada' => 'required',
					'motivo' => 'required|min:7',
					'fechaInicio' => 'required|date',
				]);
				if ($validar->fails()) {
					return redirect()->back()->withInput()->withErrors($validar->errors());
				} else {
					//Hora de entrada
					$dia = Utilerias::getDiaDB($request->fechaInicio);
					$horarios = AsignacionHorario::getHorarioPersonalDia($dia, $request->empleado);
					if (count($horarios) > 0) {
						//empezamos con la validacion de horas
						foreach ($horarios as $horario) {
							$entradaint = intval($horario->hora_entrada);
							$salidaint = intval($horario->hora_salida);
							if (($salidaint - $entradaint) > 2) {
								//es horario admon
								$asistencia = Asistencias::getAsistenciaPersonal($horario->idA, $request->empleado, $request->fechaInicio);
								$asistencias = new Asistencias();
								if (count($asistencia) > 0) {
									//Significa que si tiene un registro previo
									//Solo se hara la actualizacion a la asistencia y la insercion de la incidencia
									$asistencias->setId($asistencia[0]['id']);
									$asistencias->setHoraLlegada($request->entrada);
									$asistencias->setHoraSalida($asistencia[0]['hora_salida']);
									$asistencias->setFecha($asistencia[0]['fecha']);
									//3 para admon
									$asistencias->setEstado($asistencia[0]['estado']);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->updateAsistencia($asistencias);
									if (isset($response['success'])) {
										$response2 = $this->insertAsistencia($asistencias->getId(), $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								} else {
									//se hace la insercion de la nueva asistencia
									$asistencias->setHoraLlegada($request->entrada);
									$asistencias->setHoraSalida(null);
									$asistencias->setFecha($request->fechaInicio);
									//3 para admon
									$asistencias->setEstado(3);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->insertAsistencia($asistencias);
									if (isset($response['success'])) {
										//obtenemos el id de la asistencia dada
										$idAsistencia = $response['id'];
										$response2 = $this->insertAsistencia($idAsistencia, $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									} else {
										return redirect()->back()->with('mensaje', $response['error']);
									}
								}
							}
						}
					} else {
						return redirect()->back()->with('mensaje', 'No hay una asignación de horario para el empleado');
					}
				}
				break;
			case 2:
				$validar = Validator::make($request->all(), [
					'empleado' => 'required',
					'salida' => 'required',
					'motivo' => 'required|min:7',
					'fechaInicio' => 'required|date',
				]);
				if ($validar->fails()) {
					return redirect()->back()->withInput()->withErrors($validar->errors());
				} else {
					//Hora de salida
					$dia = Utilerias::getDiaDB($request->fechaInicio);
					$horarios = AsignacionHorario::getHorarioPersonalDia($dia, $request->empleado);
					if (count($horarios) > 0) {
						//empezamos con la validacion de horas
						foreach ($horarios as $horario) {
							$entradaint = intval($horario->hora_entrada);
							$salidaint = intval($horario->hora_salida);
							if (($salidaint - $entradaint) > 2) {
								//es horario admon
								$asistencia = Asistencias::getAsistenciaPersonal($horario->idA, $request->empleado, $request->fechaInicio);
								$asistencias = new Asistencias();
								if (count($asistencia) > 0) {
									//Significa que si tiene un registro previo
									//Solo se hara la actualizacion a la asistencia y la insercion de la incidencia
									$asistencias->setId($asistencia[0]['id']);
									$asistencias->setHoraLlegada($asistencia[0]['hora_llegada']);
									$asistencias->setHoraSalida($request->salida);
									$asistencias->setFecha($asistencia[0]['fecha']);
									//3 para admon
									$asistencias->setEstado($asistencia[0]['estado']);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->updateAsistencia($asistencias);
									if (isset($response['success'])) {
										$response2 = $this->insertAsistencia($asistencias->getId(), $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								} else {
									//se hace la insercion de la nueva asistencia
									$asistencias->setHoraLlegada(null);
									$asistencias->setHoraSalida($request->salida);
									$asistencias->setFecha($request->fechaInicio);
									//3 para admon
									$asistencias->setEstado(3);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->insertAsistencia($asistencias);
									if (isset($response['success'])) {
										//obtenemos el id de la asistencia dada
										$idAsistencia = $response['id'];
										$response2 = $this->insertAsistencia($idAsistencia, $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								}
							}
						}
					} else {
						return redirect()->back()->with('mensaje', 'No hay una asignación de horario para el empleado');
					}
				}
				break;
			case 3:
				$validar = Validator::make($request->all(), [
					'empleado' => 'required',
					'entrada' => 'required',
					'salida' => 'required',
					'motivo' => 'required|min:7',
					'fechaInicio' => 'required|date',
				]);
				if ($validar->fails()) {
					return redirect()->back()->withInput()->withErrors($validar->errors());
				} else {
					//Hora entrada y salida
					$dia = Utilerias::getDiaDB($request->fechaInicio);
					$horarios = AsignacionHorario::getHorarioPersonalDia($dia, $request->empleado);
					if (count($horarios) > 0) {
						//empezamos con la validacion de horas
						foreach ($horarios as $horario) {
							$entradaint = intval($horario->hora_entrada);
							$salidaint = intval($horario->hora_salida);
							if (($salidaint - $entradaint) > 2) {
								//es horario admon
								$asistencia = Asistencias::getAsistenciaPersonal($horario->idA, $request->empleado, $request->fechaInicio);
								$asistencias = new Asistencias();
								if (count($asistencia) > 0) {
									//Significa que si tiene un registro previo
									//Solo se hara la actualizacion a la asistencia y la insercion de la incidencia
									$asistencias->setId($asistencia[0]['id']);
									$asistencias->setHoraLlegada($request->entrada);
									$asistencias->setHoraSalida($request->salida);
									$asistencias->setFecha($asistencia[0]['fecha']);
									//3 para admon
									$asistencias->setEstado($asistencia[0]['estado']);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->updateAsistencia($asistencias);
									if (isset($response['success'])) {
										$response2 = $this->insertAsistencia($asistencias->getId(), $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								} else {
									//se hace la insercion de la nueva asistencia
									$asistencias->setHoraLlegada($request->entrada);
									$asistencias->setHoraSalida($request->salida);
									$asistencias->setFecha($request->fechaInicio);
									//3 para admon
									$asistencias->setEstado(3);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->insertAsistencia($asistencias);
									if (isset($response['success'])) {
										//obtenemos el id de la asistencia dada
										$idAsistencia = $response['id'];
										$response2 = $this->insertAsistencia($idAsistencia, $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								}
							}
						}
					} else {
						return redirect()->back()->with('mensaje', 'No hay una asignación de horario para el empleado');
					}
				}
				break;
			case 4:
				$validar = Validator::make($request->all(), [
					'empleado' => 'required',
					'entrada' => 'required',
					'salida' => 'required',
					'motivo' => 'required|min:7',
					'fechaInicio' => 'required|date',
				]);
				if ($validar->fails()) {
					return redirect()->back()->withInput()->withErrors($validar->errors());
				} else {
					//Hora entrada y salida
					$dia = Utilerias::getDiaDB($request->fechaInicio);
					$horarios = AsignacionHorario::getHorarioPersonalDia($dia, $request->empleado);
					if (count($horarios) > 0) {
						//empezamos con la validacion de horas
						foreach ($horarios as $horario) {
							$entradaint = intval($horario->hora_entrada);
							$salidaint = intval($horario->hora_salida);
							if (($salidaint - $entradaint) < 3) {
								//es horario admon
								$asistencia = Asistencias::getAsistenciaPersonal($horario->idA, $request->empleado, $request->fechaInicio);
								$asistencias = new Asistencias();
								if (count($asistencia) > 0) {
									//Significa que si tiene un registro previo
									//Solo se hara la actualizacion a la asistencia y la insercion de la incidencia
									$asistencias->setId($asistencia[0]['id']);
									$asistencias->setHoraLlegada($request->salida);
									$asistencias->setHoraSalida($request->entrada);
									//$asistencias->setHoraLlegada($request->entrada);
									//$asistencias->setHoraSalida($request->salida);
									$asistencias->setFecha($asistencia[0]['fecha']);
									//3 para admon
									$asistencias->setEstado($asistencia[0]['estado']);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->updateAsistencia($asistencias);
									if (isset($response['success'])) {
										$response2 = $this->insertAsistencia($asistencias->getId(), $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								} else {
									//se hace la insercion de la nueva asistencia
									$asistencias->setHoraLlegada($request->salida);
									$asistencias->setHoraSalida($request->entrada);
									$asistencias->setFecha($request->fechaInicio);
									//3 para admon
									$asistencias->setEstado(2);
									$asistencias->setIdEmpleado($request->empleado);
									$asistencias->setIdAsignacionHorario($horario->idA);
									$asistencias->setIdEstado(3);
									$response = $asistencias->insertAsistencia($asistencias);
									if (isset($response['success'])) {
										//obtenemos el id de la asistencia dada
										$idAsistencia = $response['id'];
										$response2 = $this->insertAsistencia($idAsistencia, $valor, $request->motivo);
										if (isset($response2['error'])) {
											return redirect()->back()->with('mensaje', $response2['error']);
										} else {
											return redirect()->action("HorarioController@incidencia")->with('mensaje', $response2['success']);
										}
									}
								}
							}
						}
					} else {
						return redirect()->back()->with('mensaje', 'No hay una asignación de horario para el empleado');
					}
				}
				break;
			default:
				$validar = Validator::make($request->all(), [
					'tipo' => 'required',
				]);
				if ($validar->fails()) {
					return redirect()->back()->withInput()->withErrors($validar->errors());
				}
				break;
		}

	}

	public function insertAsistencia($idAsistencia, $tipo, $motivo) {
		$incidencia = new Incidencias();
		$incidencia->setTipoIncidencia($tipo);
		$incidencia->setMotivo($motivo);
		$incidencia->setIdAsistencia($idAsistencia);
		$response = Incidencias::insertEntrada($incidencia);
		return $response;
	}


	public function incidenciaD() {
        //TODO agregar esto en nueva version
        $idEmpleado = \Auth::user()->empleado_id;
        $empleados = new Empleados();
        $singleEmpleado = $empleados->getSingleEmpleado($idEmpleado);
        $plantel = Plantel::find($singleEmpleado->cct_plantel);
		//recibir el plantel
		return view('PersonalViews.Horario.Asignacion.incidenciaD',
            ['empleados' => $empleados->getDocentes($singleEmpleado->cct_plantel), 'plantel' => $plantel]);
	}

	public function buscarAsignacion(Request $request) {
		//Obtenemos las variables recibidas
		$idEmpleado = $request->idEmpleado;
		$fecha = $request->fecha;
		//Buscamos la lista
		//CHECAMOS DIA POR DIA LA ASIGNACION DE HORAS QUE TIENEN
		//fecha a consultar
		$diaConsultar = Utilerias::getDiaDB($fecha);
		$horarios = Horarios::getHorariClase($idEmpleado, $diaConsultar);
		return $horarios;
	}

	/**
	 * Controllador insertIncidencia
	 * este metodo valida que los datos enviados por el formulario sean los correctos
	 * en el caso que esto no se así regresa al formulario enviando mensajes de error
	 *
	 * Si los valores son correctos se llama un metodo del
	 * modelo para hacer la insercion de la incidencia
	 *
	 * @param Request $request
	 * @return $this
	 */
	public function insertIncidenciaD(Request $request) {
		//Validamos
		$validar = Validator::make($request->all(), [
			'empleado' => 'required',
			'fechaInicio' => 'required',
			'asignacion' => 'required',
		]);
		if ($validar->fails()) {
			return redirect()->back()->withInput()->withErrors($validar->errors());
		} else {
			$idEmpleado = $request->empleado;
			$fecha = $request->fechaInicio;
			$idAsignacion = $request->asignacion;
			//hacemos las consultas pertinentes
			$diaConsultar = Utilerias::getDiaDB($fecha);
			$horarios = Horarios::getHorariClase($idEmpleado, $diaConsultar);
			foreach ($horarios as $horario) {
				if ($horario->id_asignacion_horario == $idAsignacion) {
					//se hace el guardado
					$asistencia = Asistencias::getAsistenciaPersonal($horario->id_asignacion_horario, $request->empleado, $request->fechaInicio);
					$asistencias = new Asistencias();
					if (count($asistencia) > 0) {
						//Significa que si tiene un registro previo
						//Solo se hara la actualizacion a la asistencia y la insercion de la incidencia
						$asistencias->setId($asistencia[0]['id']);
						$asistencias->setHoraLlegada($horario->hora_entrada);
						$asistencias->setHoraSalida($horario->hora_salida);
						$asistencias->setFecha($asistencia[0]['fecha']);
						//3 para admon
						$asistencias->setEstado($asistencia[0]['estado']);
						$asistencias->setIdEmpleado($idEmpleado);
						$asistencias->setIdAsignacionHorario($idAsignacion);
						$asistencias->setIdEstado(3);
						$response = $asistencias->updateAsistencia($asistencias);
						if (isset($response['success'])) {
							$response2 = $this->insertAsistencia($asistencias->getId(), 3, $request->motivo);
							if (isset($response2['error'])) {
								return redirect()->back()->with('mensaje', $response2['error']);
							} else {
								return redirect()->action("HorarioController@incidenciaD")->with('mensaje', $response2['success']);
							}
						}
					} else {
						//se hace la insercion de la nueva asistencia
						$asistencias->setHoraLlegada($horario->hora_entrada);
						$asistencias->setHoraSalida($horario->hora_salida);
						$asistencias->setFecha($fecha);
						//3 para docente
						$asistencias->setEstado(1);
						$asistencias->setIdEmpleado($idEmpleado);
						$asistencias->setIdAsignacionHorario($idAsignacion);
						$asistencias->setIdEstado(3);
						$response = $asistencias->insertAsistencia($asistencias);
						if (isset($response['success'])) {
							//obtenemos el id de la asistencia dada
							$idAsistencia = $response['id'];
							$response2 = $this->insertAsistencia($idAsistencia, 3, $request->motivo);
							if (isset($response2['error'])) {
								return redirect()->back()->with('mensaje', $response2['error']);
							} else {
								return redirect()->action("HorarioController@incidenciaD")->with('mensaje', $response2['success']);
							}
						}
					}
					break;
				}
			}
		}
	}


	public function setBloqueo(Request $request) {
		try {
			$bloqueo = Bloqueo::find($request->id);
			$bloqueo->estado = $request->estado;
			$bloqueo->save();
			return ["success" => "todo bien"];
		} catch (QueryException $e) {
			return ["error" => "error al activar bloqueo: " . $e->getMessage()];
		}

	}


	public function checkLog(Request $request) {
		$clasesAudit = AsignacionClaseAudition::getNews();
		if (count($clasesAudit) > 0) {
			return ["success" => "Hay cambios en el sistema"];
		} else {
			return $clasesAudit;
		}
	}

	public function updateLog(Request $request) {
		$clasesAudit = AsignacionClaseAudition::updateNews();
		return $clasesAudit;
	}

	public function history(){
		$lista = AsignacionClaseAudition::getLista();
		$datosEmp = Empleado::find(\Auth::user()->empleado_id);
		$plantel = Plantel::find($datosEmp->cct_plantel);
		return view('admin.logclases',['listas'=>$lista, 'plantel' => $plantel]);
	}


}
