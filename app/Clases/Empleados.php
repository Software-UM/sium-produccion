<?php
/**
 * Created by PhpStorm.
 * User: osorio
 * Date: 23/06/16
 * Time: 09:13 AM
 */

namespace App\Clases;

use App\DetalleBajas;
use App\Empleado;
use DB;
use Illuminate\Database\QueryException;


/**
 * Class Empleados
 * PHP VERSION 5.6
 * @author Miguel Angel Osorio Cruz
 * @package App\Clases
 */
class Empleados {
	private $id;
	private $nombre;
	private $apellidos;
	private $cedula;
	private $fechaNacimiento;
	private $sexo;
	private $estadoCivil;
	private $profesion;
	private $seguroSocial;
	private $cartillaMilitar;
	private $rfc;
	private $fechaIngreso;
	private $fechaSalida;
	private $curp;
	private $email;
	private $huellaDigital;
	private $foto;
	private $status;
	private $idDireccion;
	private $idPuesto;
	private $idTipoEmpleado;
	private $idDepartamento;
	private $cctPlantel;

	/**
	 * Empleados constructor.
	 */
	public function __construct() {
		$this->id = 0;
		$this->nombre = "";
		$this->apellidos = "";
		$this->cedula = "";
		$this->fechaNacimiento = date("Y-m-d");
		$this->sexo = 0;
		$this->estadoCivil = "";
		$this->profesion = "";
		$this->seguroSocial = "";
		$this->cartillaMilitar = "";
		$this->rfc = "";
		$this->fechaIngreso = date("Y-m-d");
		$this->fechaSalida = date("Y-m-d");
		$this->curp = "";
		$this->email = "";
		$this->huellaDigital = null;
		$this->foto = "";
		$this->status = "";
		$this->idDireccion = 0;
		$this->idPuesto = 0;
		$this->idTipoEmpleado = 0;
		$this->idDepartamento = 0;
		$this->cctPlantel = 0;
	}

	/**
	 * @param $id
	 * @param $nombre
	 * @param $apellidos
	 * @param $cedula
	 * @param $fechaNacimiento
	 * @param $sexo
	 * @param $estadoCivil
	 * @param $profesion
	 * @param $seguroSocial
	 * @param $cartillaMilitar
	 * @param $rfc
	 * @param $fechaIngreso
	 * @param $fechaSalida
	 * @param $curp
	 * @param $email
	 * @param $foto
	 * @param $status
	 * @param $idDireccion
	 * @param $idPuesto
	 * @param $idTipoEmpleado
	 * @param $idDepartamento
	 * @param $cctPlantel
	 * @return Empleados
	 */
	public static function withData($id, $nombre, $apellidos, $cedula, $fechaNacimiento, $sexo, $estadoCivil,
									$profesion, $seguroSocial, $cartillaMilitar, $rfc, $fechaIngreso, $fechaSalida,
									$curp, $email, $foto, $status, $idDireccion, $idPuesto, $idTipoEmpleado,
									$idDepartamento, $cctPlantel) {
		$instance = new self();
		$instance->id = $id;
		$instance->nombre = $nombre;
		$instance->apellidos = $apellidos;
		$instance->cedula = $cedula;
		$instance->fechaNacimiento = $fechaNacimiento;
		$instance->sexo = $sexo;
		$instance->estadoCivil = $estadoCivil;
		$instance->profesion = $profesion;
		$instance->seguroSocial = $seguroSocial;
		$instance->cartillaMilitar = $cartillaMilitar;
		$instance->rfc = $rfc;
		$instance->fechaIngreso = $fechaIngreso;
		$instance->fechaSalida = $fechaSalida;
		$instance->curp = $curp;
		$instance->email = $email;
		$instance->foto = $foto;
		$instance->status = $status;
		$instance->idDireccion = $idDireccion;
		$instance->idPuesto = $idPuesto;
		$instance->idTipoEmpleado = $idTipoEmpleado;
		$instance->idDepartamento = $idDepartamento;
		$instance->cctPlantel = $cctPlantel;
		return $instance;
	}


	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * @param mixed $nombre
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	/**
	 * @return mixed
	 */
	public function getApellidos() {
		return $this->apellidos;
	}

	/**
	 * @param mixed $apellidos
	 */
	public function setApellidos($apellidos) {
		$this->apellidos = $apellidos;
	}

	/**
	 * @return mixed
	 */
	public function getCedula() {
		return $this->cedula;
	}

	/**
	 * @param mixed $cedula
	 */
	public function setCedula($cedula) {
		$this->cedula = $cedula;
	}

	/**
	 * @return mixed
	 */
	public function getFechaNacimiento() {
		return $this->fechaNacimiento;
	}

	/**
	 * @param mixed $fechaNacimiento
	 */
	public function setFechaNacimiento($fechaNacimiento) {
		$this->fechaNacimiento = $fechaNacimiento;
	}

	/**
	 * @return mixed
	 */
	public function getSexo() {
		return $this->sexo;
	}

	/**
	 * @param mixed $sexo
	 */
	public function setSexo($sexo) {
		$this->sexo = $sexo;
	}

	/**
	 * @return mixed
	 */
	public function getEstadoCivil() {
		return $this->estadoCivil;
	}

	/**
	 * @param mixed $estadoCivil
	 */
	public function setEstadoCivil($estadoCivil) {
		$this->estadoCivil = $estadoCivil;
	}

	/**
	 * @return mixed
	 */
	public function getProfesion() {
		return $this->profesion;
	}

	/**
	 * @param mixed $profesion
	 */
	public function setProfesion($profesion) {
		$this->profesion = $profesion;
	}

	/**
	 * @return mixed
	 */
	public function getSeguroSocial() {
		return $this->seguroSocial;
	}

	/**
	 * @param mixed $seguroSocial
	 */
	public function setSeguroSocial($seguroSocial) {
		$this->seguroSocial = $seguroSocial;
	}

	/**
	 * @return mixed
	 */
	public function getCartillaMilitar() {
		return $this->cartillaMilitar;
	}

	/**
	 * @param mixed $cartillaMilitar
	 */
	public function setCartillaMilitar($cartillaMilitar) {
		$this->cartillaMilitar = $cartillaMilitar;
	}

	/**
	 * @return mixed
	 */
	public function getRfc() {
		return $this->rfc;
	}

	/**
	 * @param mixed $rfc
	 */
	public function setRfc($rfc) {
		$this->rfc = $rfc;
	}

	/**
	 * @return mixed
	 */
	public function getFechaIngreso() {
		return $this->fechaIngreso;
	}

	/**
	 * @param mixed $fechaIngreso
	 */
	public function setFechaIngreso($fechaIngreso) {
		$this->fechaIngreso = $fechaIngreso;
	}

	/**
	 * @return mixed
	 */
	public function getFechaSalida() {
		return $this->fechaSalida;
	}

	/**
	 * @param mixed $fechaSalida
	 */
	public function setFechaSalida($fechaSalida) {
		$this->fechaSalida = $fechaSalida;
	}

	/**
	 * @return mixed
	 */
	public function getCurp() {
		return $this->curp;
	}

	/**
	 * @param mixed $curp
	 */
	public function setCurp($curp) {
		$this->curp = $curp;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getHuellaDigital() {
		return $this->huellaDigital;
	}

	/**
	 * @param mixed $huellaDigital
	 */
	public function setHuellaDigital($huellaDigital) {
		$this->huellaDigital = $huellaDigital;
	}

	/**
	 * @return mixed
	 */
	public function getFoto() {
		return $this->foto;
	}

	/**
	 * @param mixed $foto
	 */
	public function setFoto($foto) {
		$this->foto = $foto;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	public function getIdDireccion() {
		return $this->idDireccion;
	}

	/**
	 * @param mixed $idDireccion
	 */
	public function setIdDireccion($idDireccion) {
		$this->idDireccion = $idDireccion;
	}

	/**
	 * @return mixed
	 */
	public function getIdPuesto() {
		return $this->idPuesto;
	}

	/**
	 * @param mixed $idPuesto
	 */
	public function setIdPuesto($idPuesto) {
		$this->idPuesto = $idPuesto;
	}

	/**
	 * @return mixed
	 */
	public function getIdTipoEmpleado() {
		return $this->idTipoEmpleado;
	}

	/**
	 * @param mixed $idTipoEmpleado
	 */
	public function setIdTipoEmpleado($idTipoEmpleado) {
		$this->idTipoEmpleado = $idTipoEmpleado;
	}

	/**
	 * @return mixed
	 */
	public function getIdDepartamento() {
		return $this->idDepartamento;
	}

	/**
	 * @param mixed $idDepartamento
	 */
	public function setIdDepartamento($idDepartamento) {
		$this->idDepartamento = $idDepartamento;
	}

	/**
	 * @return mixed
	 */
	public function getCctPlantel() {
		return $this->cctPlantel;
	}

	/**
	 * @param mixed $cctPlantel
	 */
	public function setCctPlantel($cctPlantel) {
		$this->cctPlantel = $cctPlantel;
	}

	/**
	 * getEmpleados fuction
	 * Lista general de los empleado
	 * VALORES RETORNADOS (ID EMPLEADO, NOMBRE COMPLETO, DIRECCION, PUESTO Y DEPARTAMENTO)
	 * @return Empleado
	 */
	public function getEmpleados($plantel) {
		try {
			$listEmpleado = Empleado::join('plantel', 'cct', '=', 'cct_plantel')
				->join('puesto', 'puesto.id', '=', 'id_puesto')
				->join('departamento', 'departamento.id', '=', 'id_departamento')
				->where('status', 1)
                ->where('cct_plantel',$plantel)
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'),
					'empleado.created_at', 'empleado.profesion', 'puesto.descripcion',
					'departamento.nombre as depa')->get();
			return $listEmpleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * obtiene los detalles del empleado no todos
	 * @param $idEmpleado
	 * @return array
	 */
	public function getDetalleEmpleados($idEmpleado) {
		try {
			$listEmpleado = Empleado::join('plantel', 'cct', '=', 'cct_plantel')
				->join('puesto', 'puesto.id', '=', 'id_puesto')
				->join('departamento', 'departamento.id', '=', 'id_departamento')
				->where([['status', 1], ['empleado.id', $idEmpleado]])
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'),
					'empleado.created_at', 'empleado.profesion', 'puesto.descripcion',
					'departamento.nombre as depa')->get();
			return $listEmpleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene el id y el nombre del empleado por departamento
	 * @param $idDepto
	 * @return array
	 */
	public function getEmpleadoDepa($idDepto) {
		try {
			$listEmpleado = Empleado::join('plantel', 'cct', '=', 'cct_plantel')
				->join('direccion', 'direccion.id', '=', 'empleado.id_direccion')
				->join('puesto', 'puesto.id', '=', 'id_puesto')
				->join('departamento', 'departamento.id', '=', 'id_departamento')
				->where([['status', 1], ['departamento.id', $idDepto]])
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'))->get();
			return $listEmpleado;
		} catch (QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene el id y el nombre del empleado por departamento
	 * @param $idDepto
	 * @return array
	 */
	public function getEmpleadoDepaLibre($idDepto,$cct) {
		try {
			$listEmpleado = Empleado::leftJoin('asignacion_horario', 'id_empleado', '=', 'empleado.id')
				->whereNull('id_empleado')
				->where([['status', 1], ['id_departamento', $idDepto],['empleado.cct_plantel',$cct]])
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'))->get();
			return $listEmpleado;
		} catch (QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene el Objeto del empleado mediante su identificador
	 * @param $id
	 * @return new Empleados objeto
	 */
	public function getSingleEmpleado($id) {
		try {
			$empleado = Empleado::find($id);

			$this->setId($empleado->id);
			$this->setNombre($empleado->nombre);
			$this->setApellidos($empleado->apellidos);
			$this->setCedula($empleado->cedula);
			$this->setFechaNacimiento($empleado->fecha_nacimiento);
			$this->setSexo($empleado->sexo);
			$this->setEstadoCivil($empleado->estado_civil);
			$this->setProfesion($empleado->profesion);
			$this->setSeguroSocial($empleado->seguro_social);
			$this->setCartillaMilitar($empleado->cartilla_militar);
			$this->setRfc($empleado->rfc);
			$this->setCurp($empleado->curp);
			$this->setEmail($empleado->email);
			$this->setStatus($empleado->status);
			$this->setIdDireccion($empleado->id_direccion);
			$this->setIdPuesto($empleado->id_puesto);
			$this->setIdDepartamento($empleado->id_departamento);
			$this->setIdTipoEmpleado($empleado->id_tipo_empleado);
			$this->setCctPlantel($empleado->cct_plantel);
			return $empleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener el empleado:' . $e->getMessage()];
		} catch (\Exception $e) {
			return ['error' => 'Falló al obtener el empleado:' . $e->__toString()];
		}

	}

	/**
	 * getEmpleados function
	 *
	 * Método que retorna la lista general de los
	 * empleados por centro de trabajo
	 * VALORES RETORNADOS (ID EMPLEADO, NOMBRE COMPLETO, DIRECCION, PUESTO Y DEPARTAMENTO)
	 * @param $cct
	 * @return Empleado
	 */
	public function getEmpleadosPlantel($cct) {
		try {
			$listEmpleado = Empleado::join('plantel', 'cct', '=', 'cct_plantel')
				->join('puesto', 'puesto.id', '=', 'id_puesto')
				->join('departamento', 'departamento.id', '=', 'id_departamento')
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'), 'puesto.descripcion',
					'departamento.nombre as depa')->where([['cct', $cct], ['status', 1]])->get();
			return $listEmpleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * Obtiene los empleados docentes por plantel
	 * @param $cct
	 * @return array
	 */
	public function getDocentes($cct) {
		try {
			$listEmpleado = Empleado::join('plantel', 'cct', '=', 'cct_plantel')
				->join('tipo_empleado', 'tipo_empleado.id', '=', 'id_tipo_empleado')
				->select('empleado.id', DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'), 'empleado.clave')
				->where([['cct', '=', $cct], ['tipo_empleado.descripcion', 'like', '%DOCENTE%'], ['status', 1]])
				->orderBy('nombre', 'asc')->get();
			return $listEmpleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al obtener la lista de los empleados:' . $e->getMessage()];
		}
	}

	/**
	 * Agrega un nuevo empleado
	 * @param Empleados $empleados
	 * @return array
	 */
	public function insertEmpleado(Empleados $empleados) {
		try {
			$modelEmpleado = new Empleado();
			$modelEmpleado->nombre = $empleados->getNombre();
			$modelEmpleado->apellidos = $empleados->getApellidos();
			$modelEmpleado->cedula = $empleados->getCedula();
			$modelEmpleado->fecha_nacimiento = $empleados->getFechaNacimiento();
			$modelEmpleado->sexo = $empleados->getSexo();
			$modelEmpleado->estado_civil = $empleados->getEstadoCivil();
			$modelEmpleado->profesion = $empleados->getProfesion();
			$modelEmpleado->seguro_social = $empleados->getSeguroSocial();
			$modelEmpleado->cartilla_militar = $empleados->getCartillaMilitar();
			$modelEmpleado->rfc = $empleados->getRfc();
			/*$modelEmpleado->fecha_ingreso = $empleados->getFechaIngreso();
			$modelEmpleado->fecha_salida = $empleados->getFechaSalida();*/
			$modelEmpleado->curp = $empleados->getCurp();
			$modelEmpleado->email = $empleados->getEmail();
			//$modelEmpleado->foto = $empleados->getFoto();
			$modelEmpleado->status = $empleados->getStatus();
			$modelEmpleado->id_direccion = $empleados->getIdDireccion();
			$modelEmpleado->id_puesto = $empleados->getIdPuesto();
			$modelEmpleado->id_tipo_empleado = $empleados->getIdTipoEmpleado();
			$modelEmpleado->id_departamento = $empleados->getIdDepartamento();
			$modelEmpleado->cct_plantel = $empleados->getCctPlantel();
			$modelEmpleado->save();
			return ['success' => 'Se dió de alta al Empleado: ' . $empleados->getNombre()];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al insertar Empleado: ' . $e->getMessage()];
		}
	}

	/**
	 * Actualiza un nuevo empleado
	 * @param Empleados $empleados
	 * @return array
	 */
	public function updateEmpleado(Empleados $empleados) {
		try {
			$modelEmpleado = Empleado::find($empleados->getId());
			$modelEmpleado->nombre = $empleados->getNombre();
			$modelEmpleado->apellidos = $empleados->getApellidos();
			$modelEmpleado->cedula = $empleados->getCedula();
			$modelEmpleado->fecha_nacimiento = $empleados->getFechaNacimiento();
			$modelEmpleado->sexo = $empleados->getSexo();
			$modelEmpleado->estado_civil = $empleados->getEstadoCivil();
			$modelEmpleado->profesion = $empleados->getProfesion();
			$modelEmpleado->seguro_social = $empleados->getSeguroSocial();
			$modelEmpleado->cartilla_militar = $empleados->getCartillaMilitar();
			$modelEmpleado->rfc = $empleados->getRfc();
			//$modelEmpleado->fecha_ingreso = $empleados->getFechaIngreso();
			//$modelEmpleado->fecha_salida = $empleados->getFechaSalida();
			$modelEmpleado->curp = $empleados->getCurp();
			$modelEmpleado->email = $empleados->getEmail();
			//$modelEmpleado->foto = $empleados->getFoto();
			$modelEmpleado->status = $empleados->getStatus();
			$modelEmpleado->id_direccion = $empleados->getIdDireccion();
			$modelEmpleado->id_puesto = $empleados->getIdPuesto();
			$modelEmpleado->id_tipo_empleado = $empleados->getIdTipoEmpleado();
			$modelEmpleado->id_departamento = $empleados->getIdDepartamento();
			$modelEmpleado->cct_plantel = $empleados->getCctPlantel();
			$modelEmpleado->save();
			return ['success' => 'Se actualizó al Empleado: ' . $empleados->getNombre()];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al actualizar Empleado: ' . $e->getMessage()];
		}
	}

	/**
	 * Incomplete
	 * @param $idEmpleado
	 * @return array
	 */
	public function deleteEmpleado($idEmpleado, $descripcion, $idtipoBaja, $finiquito, $liquidacion) {
		try {
			$modelEmpleado = Empleado::find($idEmpleado);
			$modelEmpleado->status = 2;
			$modelEmpleado->save();
			$detalleBaja = new DetalleBajas();
			$detalleBaja->descripcion = $descripcion;
			$detalleBaja->fecha_baja = date("Y-m-d");
			$detalleBaja->finiquito = $finiquito;
			$detalleBaja->liquidacion = $liquidacion;
			$detalleBaja->id_tipo_bajas = $idtipoBaja;
			$detalleBaja->id_empleado = $idEmpleado;
			$detalleBaja->save();
			return ['success' => 'Se elimino al empleado'];
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'Falló al eliminar Empleado: ' . $e->getMessage()];
		} catch (\Exception $e) {
			return ['error' => 'Falló al eliminar el empleado:' . $e->__toString()];
		}
	}

	/**
	 * obtiene la asigancion de horario por ciclo
	 * @param $idCiclo
	 * @param $idModalidad
	 * @return array
	 */
	public static function reporteDocenteGeneral($idCiclo, $idModalidad,$cct) {
		try {
			$empleado = Empleado::join('asignacion_horario as h', 'empleado.id', '=', 'h.id_empleado')
				->join('asignacion_clase as c', 'h.id', '=', 'c.id_asignacion_horario')
				->join('grupos as g', 'c.id_grupos', '=', 'g.id')
				->select(DB::raw('COUNT(empleado.id) as id'),
					DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'))
				->where([['id_ciclos', $idCiclo],['cct_plantel',$cct]])->groupBy('empleado.id')->orderBy("nombre")->get();
			return $empleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'fallo al obtener la lista de los docentes' . $e->getMessage()];
		}
	}

	/**
	 * recibe el plantel a imprimir
	 * @return array
	 */
	public static function getAdmons($cct) {
		try {
			$empleado = Empleado::select('empleado.id as id',
				DB::raw('CONCAT(empleado.nombre,\' \', empleado.apellidos) as nombre'))
				->where([['cct_plantel', '=', $cct], ['id_puesto', '<>', 9],['status',1]])->get();
			return $empleado;
		} catch (\Illuminate\Database\QueryException $e) {
			return ['error' => 'fallo al obtener la lista de los docentes' . $e->getMessage()];
		}
	}

}