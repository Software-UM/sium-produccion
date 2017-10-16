<?php

namespace App\Http\Controllers;

use App\Clases\AsignacionClase;
use App\Clases\AsignacionHorario;
use App\Clases\Asistencias;
use App\Clases\Carrera;
use App\Clases\Ciclo;
use App\Clases\Grupo;
use App\Clases\Horarios;
use App\Clases\Utilerias;
use App\Salones;
use App\UserDocente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Clases\Empleados;
use App\Clases\Planteles;
use App\Clases\TipoEmpleados;
use App\Clases\Puestos;
use App\Clases\Direcciones;
use App\TipoHorarios;
use App\Estados;
use App\TipoBaja;
use App\DetalleBajas;
use App\Clases\Departamentos;
use App\Municipios;
use App\Clases\BarcodeQR;
use App\User;
use App\Empleado;
use App\Plantel;

class PersonalController extends Controller {

	//TODO agregar esto en nueva version
	public function inicio() {
        //obtenemos el id del empleado logueado
        $idEmpleado = \Auth::user()->empleado_id;
		$empleados = new Empleados();
        $singleEmpleado = $empleados->getSingleEmpleado($idEmpleado);
        $datosEmp = Empleado::find(Auth::user()->empleado_id);
        $plantel = Plantel::find($datosEmp->cct_plantel);
        $tipoBajas = TipoBaja::all();
		return view('PersonalViews.inicio', ['empleados' => $empleados->getEmpleados($singleEmpleado->cct_plantel), 'tipos' => $tipoBajas,
			        'plantel'=>$plantel]);
	}
    //TODO agregar esto en nueva version
	public function register(){
        $idEmpleado = \Auth::user()->empleado_id;
		$empleado = new Empleados();
        $singleEmpleado = $empleado->getSingleEmpleado($idEmpleado);
		$empleados = $empleado->getEmpleados($singleEmpleado->cct_plantel);
		$plantel = Plantel::find($singleEmpleado->cct_plantel);
		return view('auth.register',['empleados'=>$empleados, 'plantel' => $plantel]);
	}

	public function registerPost(Request $request){
		$validacion = $this->validator($request->all());
		if ($validacion->fails()){
			return redirect()->back()->withInput()->withErrors($validacion->errors());
		}else{
			//se hace el registro
			$usuario = $this->create($request->all());
			return redirect('/admin/register');
		}
	}
	
	public function registerDocente(){
        $idEmpleado = \Auth::user()->empleado_id;
		$empleado = new Empleados();
        $singleEmpleado = $empleado->getSingleEmpleado($idEmpleado);
		$empleados = $empleado->getDocentes($singleEmpleado->cct_plantel);
		$plantel = Plantel::find($singleEmpleado->cct_plantel);
		return view('docente.register',['empleados'=>$empleados, 'plantel' => $plantel]);
	}

	public function registerPostDocente(Request $request){
		$validacion = $this->validatorDocente($request->all());
		if ($validacion->fails()){
			return redirect()->back()->withInput()->withErrors($validacion->errors());
		}else{
			//se hace el registro
			$usuario = $this->createDocentes($request->all());
			return redirect('/docente/register');
		}
	}
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'empleado' => 'required',
		]);
	}
	protected function validatorDocente(array $data)
	{
		return Validator::make($data, [
			'usuario' => 'required|max:255|unique:users_docentes',
			'password' => 'required|min:6|confirmed',
			'empleado' => 'required',
		]);
	}
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		return User::create([
			'email' => strtolower($data['email']),
			'password' => bcrypt($data['password']),
			'empleado_id' =>$data['empleado'],
			'tipo' =>$data['role'],
		]);
	}
	protected function createDocentes(array $data)
	{
		return UserDocente::create([
			'usuario' => $data['usuario'],
			'password' => bcrypt($data['password']),
			'empleado_id' =>$data['empleado'],
			'tipo' =>$data['role'],
		]);
	}
	public function add() {
		$estados = Estados::all();
		$plantel = new Planteles();
		$tipoEmpleado = new TipoEmpleados();
		$puestos = new Puestos();
		$departamento = new Departamentos();
		return view('PersonalViews.form', ['planteles' => $plantel->getPlanteles(),
			'tipos' => $tipoEmpleado->getTipos(), 'puestos' => $puestos->getPuestos(),
			'departamentos' => $departamento->getDepartamentos(), 'estados' => $estados]);
	}

	public function save(Request $request) {

		/**
		 *Subir foto
		 */
		/**
		 * $file = $request->file('foto');
		 * //verificamos el tipo de archivo que subio el usuario
		 * $extension = $file->getClientOriginalExtension();
		 * $nombreFoto = $file->getFilename();
		 * $file->move("uploads",$nombreFoto.".".$extension);**/
		$validacion = $this->validarForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withInput()->withErrors($validacion->errors());
		} else {
			//procedemos a la insercion de datos
			$direccion = Direcciones::withData(0,
				$request->calle, $request->colonia, $request->cp + 0,
				$request->municipio, $request->estados);
			$response = $direccion->insertDireccion($direccion);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else {
				$empleado = Empleados::withData(0, $request->nombre, $request->apellidos,
					$request->cedula + 0, $request->fechaNacimiento, $request->sexo, $request->civil,
					$request->profesion, $request->seguro, $request->cartilla, $request->rfc, "", "",
					$request->curp, $request->mail, "", 1, $response["id"], $request->puesto, $request->tipo,
					$request->departamento, $request->plantel);
				$responses = $empleado->insertEmpleado($empleado);
				if (isset($responses['error'])) {
					return redirect()->back()->with('mensaje', $responses['error']);
				} else {
					return redirect()->action("PersonalController@inicio")->with('mensaje', $responses['success']);
				}
			}
			//$empleado = Empleados::withData(0,$request->nombre,$request->apellidos,
			//	);
		}
	}

	public function saveFoto(Request $request){
		//$idEmpleado = $request->id;
		/**
		 *Subir foto
		 */
		 $file = $request->file('foto');
		 //verificamos el tipo de archivo que subio el usuario
		 $extension = $file->getClientOriginalExtension();
		 $nombreFoto = $file->getFilename();
		 $file->move("uploads",$request->id.".jpg");
		echo "OK";
	}


	public function modificar(Request $request) {
		//buscamos las tablas de referencia del empleado
		$empleado = new Empleados();
		$response = $empleado->getSingleEmpleado($request->id);
		if (isset($response['error'])) {
			return redirect()->action("PersonalController@inicio")->with('error', $response['error']);
		} else {
			$estados = Estados::all();
			$plantel = new Planteles();
			$tipoEmpleado = new TipoEmpleados();
			$puestos = new Puestos();
			$departamento = new Departamentos();
			$direccion = new Direcciones();
			$direccion->getDireccion($empleado->getIdDireccion());
			$municipios = Municipios::where('id_estado', $direccion->getEstado())->get();
			$params = ['empleado' => $empleado, 'planteles' => $plantel->getPlanteles(),
				'tipos' => $tipoEmpleado->getTipos(), 'puestos' => $puestos->getPuestos(),
				'departamentos' => $departamento->getDepartamentos(), 'estados' => $estados,
				'direccion' => $direccion, 'municipio' => $municipios];
			return view("PersonalViews.formMod", $params);
		}
	}

	public function saveMod(Request $request) {
		$validacion = $this->validarForm($request);
		if ($validacion->fails()) {
			return redirect()->back()->withErrors($validacion->errors());
		} else {
			$direccion = Direcciones::withData($request->idDireccion, $request->calle, $request->colonia, $request->cp + 0,
				$request->municipio, $request->estados);
			$response = $direccion->updateDireccion($direccion);
			if (isset($response['error'])) {
				return redirect()->back()->with('mensaje', $response['error']);
			} else {
				$empleado = Empleados::withData($request->id, $request->nombre, $request->apellidos,
					$request->cedula, $request->fechaNacimiento, $request->sexo, $request->civil,
					$request->profesion, $request->seguro, $request->cartilla, $request->rfc, "", "",
					$request->curp, $request->mail, "", 1, $response['id'], $request->puesto, $request->tipo,
					$request->departamento, $request->plantel);
				$response = [];
				$response = $empleado->updateEmpleado($empleado);
				if (isset($response['error'])) {
					return redirect()->back()->with('mensaje', $response['error']);
				} else {
					return redirect()->action("PersonalController@inicio")->with('mensaje', $response['success']);
				}
			}
		}
	}

	public function delete(Request $request) {
		$empleados = new Empleados();
		$response = $empleados->deleteEmpleado($request->idP, $request->descripcion, $request->tipoBaja,
			0.00, 0.00);
		if (isset($response['error'])) {
			return redirect()->action("PersonalController@inicio")->with('error', $response['error']);
		} else {
			return redirect()->action("PersonalController@inicio")->with('mensaje', $response['success']);
		}
	}


	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function validarForm(Request $request) {
		$validacion = Validator::make($request->all(), [
			'nombre' => 'required|max:255',
			'apellidos' => 'required|max:255',
			'fechaNacimiento' => 'required|date',
			'profesion' => 'required|max:255',
			'foto' => 'file|image',
			'curp' => 'max:20',
			'municipio' => 'required',
			'calle' => 'required',
			'colonia' => 'required',
			'cp' => 'numeric',
		]);

		return $validacion;
	}

	public function getempleado(Request $request) {
        $idEmpleado = \Auth::user()->empleado_id;
		$empleado = new Empleados();
        $singleEmpleado = $empleado->getSingleEmpleado($idEmpleado);
		return $empleado->getEmpleadoDepaLibre($request->id,$singleEmpleado->cct_plantel);
	}

	public function horario(Request $request) {
		//obtenemos la informacion del empleado
		$empleado = new Empleados();
		$empleado->getSingleEmpleado($request->id);
		//obtenemos los horarios del empleado asi como su descripcion
		$horarios = new AsignacionHorario();
		$tipos = TipoHorarios::all();
		return view('PersonalViews.horario', ['empleados' => $empleado,
			'horarios' => $horarios->getHorarioPersonal($empleado->getId()),'tipos'=>$tipos]);
	}

	public function takeFoto(Request $request) {
		$empleado = new Empleados();
		$empleados = $empleado->getDetalleEmpleados($request->id);
		return view('PersonalViews.formFoto',['empleados'=>$empleados]);
	}


	/***
	 * GENERACION DE CÓDIGOS QR
	 */

	/**
	 * Genera el
	 * @param Request $request
	 */
	/*public function generarQR(Request $request){
		$empleados = new Empleados();
		$empleados->getSingleEmpleado($request->id);
		//make route
		$urlPre = "components/qr-codes/".$empleados->getId().".png";
		$url =  url($urlPre);
		//generamos el qr de empleado
		if (!file_exists($urlPre)){
			$idEncriptado = Crypt::encrypt($empleados->getId());
			$qr = new BarcodeQR();
			// create URL QR code
			$qr->url("192.168.1.4/modules/personal/asistencia/".$idEncriptado);
			// display new QR code image
			$qr->draw(250, $urlPre);
		}
		//descargamos la imagen
		return response()->download($urlPre);
	}*/
	public function generarQR(Request $request) {
		$empleados = new Empleados();
		$empleados->getSingleEmpleado($request->id);
		//make route
		$urlPre = "components/qr-codes/" . $empleados->getNombre() . " " . $empleados->getApellidos() . "-". $request->id . ".png";
		$url = url($urlPre);
		//generamos el qr de empleado
		if (!file_exists($urlPre)) {
			$idEncriptado = Crypt::encrypt($empleados->getId());
			$qr = new BarcodeQR();
			// create URL QR code
			$qr->text($idEncriptado." 1");
			// display new QR code image
			$qr->draw(188, $urlPre);
		}
		//descargamos la imagen
		return response()->download($urlPre);
	}

	public function generarQRSalon(Request $request){
		$ciclo = new Ciclo();
		$ciclo->getCiclo($request->idCiclo);
		$grupo = new Grupo();
		$grupo->getGrupoOb($request->idGrupo);
		$carrera = new Carrera();
		$carrera->getCarrera($request->idCarrera);
		$salon = $request->salon;
		$allSalones = Salones::join('edificio','edificio.id','=','id_edificio')->where('salon.id',$salon)->first();
		$asignacionClase = new AsignacionClase();
		$asignacionClase->setIdCarreras($carrera->getId());
		$asignacionClase->setIdGrupo($grupo->getId());
		$asignacionClase->setIdCiclos($ciclo->getId());
		$asignacionClase->setSalon($salon);
		
		$campus = '';
		if($allSalones->cct_plantel == 1)
			$campus = 'TUXTLA';
		else if($allSalones->cct_plantel == 2)
			$campus = 'TAPACHULA';
		else
			$campus = 'CANCUN';

		//Storage::makeDirectory("/video/hola.txt",0777,true);
		$ruta = "";
		if (isset($request->dia)){
			if ($request->dia == 5){
				$ruta = "components/qr-codes/".$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero."-SABADOS";
				$path = public_path().'/components/qr-codes/'.$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero."-SABADOS";
				File::makeDirectory($path, $mode = 0777, true, true);
				$empleados = $asignacionClase->getEmpleadosCargaDia($request->dia);
			}else{
				$ruta = "components/qr-codes/".$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero."-DOMINGOS";
				$path = public_path().'/components/qr-codes/'.$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero."-DOMINGOS";
				File::makeDirectory($path, $mode = 0777, true, true);
				$empleados = $asignacionClase->getEmpleadosCargaDia($request->dia);
			}
		}else{
			$path = public_path().'/components/qr-codes/'.$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero;
			File::makeDirectory($path, $mode = 0777, true, true);
			$ruta = "components/qr-codes/".$campus.'-'.$allSalones->nombre.'-salon-'.$allSalones->numero;
			$empleados = $asignacionClase->getEmpleadosCarga();
		}
		foreach ($empleados as $empleado){
			$urlPre = $ruta."/" . $empleado->nombre . " " . $empleado->apellidos . " - ".$empleado->id_empleado.".png";
			$url = url($urlPre);
			//generamos el qr de empleado
			if (!file_exists($urlPre)) {
				$idEncriptado = Crypt::encrypt($empleado->id_empleado);
				$qr = new BarcodeQR();
				// create URL QR code
				$qr->text($idEncriptado." ".$allSalones->numero);
				// display new QR code image
				$qr->draw(188, $urlPre);
			}
		}
		return redirect()->back();
	}

	public function asistenciaDocente(Request $request){
		$idEmpleado = Crypt::decrypt($request->id);
		$idEmpleado2 = Auth::user()->empleado_id;
		$empleados = new Empleados();
		$empleados->getSingleEmpleado($idEmpleado);
		if ($idEmpleado == $idEmpleado2){
			$fechaTomada = date('Y-m-d');
			$diaConsultar = Utilerias::getDiaDB($fechaTomada);
			//Buscamos la asignacion de horario del docente
			$horarios = Horarios::getHorariClase($empleados->getId(),$diaConsultar);
			$asistencia = new Asistencias();
			//$horarios = AsignacionHorario::getHorarioPersonalDia(, );
			//se encontro algun horario para este docente
			$horarioActual = date("Y-m-d h:i:s");
			$valor = 0;
			if (count($horarios) > 0) {
				$hora = date("h:i:s");
				foreach ($horarios as $horario){
					$compara1 = date('Y-m-d h:i:s',strtotime($fechaTomada . " ".$horario->hora_entrada));
					$compara2 = date('Y-m-d h:i:s',strtotime($fechaTomada ." ".$horario->hora_salida));
					$valor = $asistencia->compararHoras($horarioActual,$compara1,$compara2,$horario,$idEmpleado,$hora);
				}
			}else{
				$valor = 0;
			}
			$parametros = ['respuesta'=>$valor, 'empleado'=>$empleados, 'hora'=>$horarioActual];
		}else{
			$valor = 6;
			$horarioActual = date("Y-m-d h:i:s");
			$parametros = ['respuesta'=>$valor, 'empleado'=>$empleados, 'hora'=>$horarioActual];
		}
		return view('PersonalViews.Horario.checador.checador',$parametros);
	}
}
