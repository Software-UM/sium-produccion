<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

	Route::get('/', 'HomeController@index');
	Route::auth();
	#Rutas para el checador en Android y Iphone
	Route::post('/docentes/login/','Docente\HomeController@loginDocenteApp');
	Route::post('/docentes/logina/','Docente\HomeController@loginAdminApp');
	Route::post('/docentes/check/','Docente\HomeController@asistenciaDocente');
	Route::get('/docente/iphone_docente/{qrcode}/{numsalon}', 'Docente\IphoneController@asistenciaDocente');
	Route::get('/docente/iphone_admin/{qrcode}', 'Docente\IphoneController@asistenciaAdmin');
	Route::post('/docentes/checka/','Docente\HomeController@asistenciaAdmin');
	Route::get('/docente/login','Docente\HomeController@showLoginForm');
	Route::post('/docente/login','Docente\HomeController@login');
	Route::get('/docente/logout', 'Docente\HomeController@logout');
	#End! Rutas para checador Android y Iphone
	Route::group(['middleware' => ['auth:docente']], function (){
		Route::get('/docente/dashboard', 'Docente\HomeController@index');
		/** Generar código QR */
		Route::get('modules/personal/asistencia/{id}','PersonalController@asistenciaDocente');
	});
	Route::group(['middleware' => ['auth']], function () {
		/**
		 * Modulo general de RH
		 */
		//Rutas del super usuario
		Route::get("/admin/","AdminController@index");
		Route::get("/admin/personal/","AdminController@personal");
		Route::get("/admin/accesos/","AdminController@accesos");
		Route::get("/admin/bugs/","AdminController@bugs");
		Route::get("/admin/movimientos/","AdminController@movimientos");
		//Metodo de registro
		Route::get('/admin/register','PersonalController@register');
		Route::post('/admin/register','PersonalController@registerPost');
		Route::get('/docente/register','PersonalController@registerDocente');
		Route::post('/docente/register','PersonalController@registerPostDocente');
		//Asignamos privilegios
		/**
		 * Modulo personal
		 */
		Route::get('modules/personal/','PersonalController@inicio');
		Route::get('modules/personal/add','PersonalController@add');
		Route::post('modules/personal/add/municipios','PlantelController@municipios');
		Route::post('modules/personal/add','PersonalController@save');
		Route::get('modules/personal/modify/{id}','PersonalController@modificar');
		Route::post('modules/personal/modify','PersonalController@saveMod');
		Route::post('modules/personal/delete','PersonalController@delete');
		Route::get('modules/personal/horario/empleado/{id}','PersonalController@horario');
		Route::post('modules/personal/horario/elim/single','HorarioController@dropSingle');
		Route::get('modules/personal/foto/{id}','PersonalController@takeFoto');
		Route::post('modules/personal/foto','PersonalController@saveFoto');

		//End modulo Personal
		/**
		 * Modulo horarios
		 */
		Route::get('modules/personal/horario','HorarioController@inicio');
		Route::get('modules/personal/horario/add','HorarioController@add');
		Route::post('modules/personal/horario/add','HorarioController@agregar');
		Route::post('modules/personal/horario/add/single','HorarioController@agregarSingle');
		Route::get('modules/personal/horario/modify/{id}','HorarioController@modificar');
		//Asignacion Horario Personal
		Route::get('modules/personal/horario/asignacion','HorarioController@inicioAsignacion');
		Route::get('modules/personal/horario/asignacion/add','HorarioController@asignacionAdd');
		Route::post('modules/personal/horario/asignacion/add','HorarioController@saveAsignacion');
		Route::post('modules/personal/horario/asignacion/add/getempleado','PersonalController@getempleado');
		//tipo Horario
		Route::get('modules/personal/horario/tipo','HorarioController@tipo');
		Route::get('modules/personal/horario/tipo/add','HorarioController@addTipo');
		Route::post('modules/personal/horario/tipo/add','HorarioController@saveTipo');
		Route::get('modules/personal/horario/tipo/modify/{id}','HorarioController@modifyTipo');
		Route::post('modules/personal/horario/tipo/modify','HorarioController@saveModTipo');
		Route::post('modules/personal/horario/tipo/eliminar','HorarioController@eliminarTipo');
		//asignacion grupal
		Route::get('modules/personal/horario/grupo','HorarioController@grupo');
		Route::get('modules/personal/horario/grupo/addGrupo','HorarioController@addGrupo');
		Route::get('modules/personal/horario/grupo/seleccionar','CarreraController@seleccionar');
		Route::post('modules/personal/horario/grupo/seleccionar','HorarioController@asignarGrupo');
		Route::get('modules/personal/horario/grupo/salones','HorarioController@salones');
		Route::post('modules/personal/horario/grupo/salones','HorarioController@updateSalones');
		Route::get('modules/personal/horario/grupo/salones/validar','HorarioController@validar');
		//validaciones para saber si ya hay alguna materia asignada en el mismo dia y hora para el docente
		Route::post('modules/personal/horario/grupo/validar','HorarioController@validador');
		Route::post('modules/personal/horario/grupo/validarp','HorarioController@validadorPosgrado');
		Route::get('modules/personal/horario/asignacionp/','HorarioController@getAsignacionp');
		//eliminacion de fechas asignadas en posgrado
		Route::get('modules/personal/horario/fechas','HorarioController@buscarFechas');
		Route::delete('modules/personal/horario/fechas','HorarioController@deleteFecha');
		Route::get('modules/personal/horario/grupo/reporte/{idGrupo}/carrera/{idCarrera}/ciclo/{idCiclo}/mod/{idMod}','HorarioController@reporte');
		Route::get('modules/personal/horario/grupo/modify/{idGrupo}/carrera/{idCarrera}/ciclo/{idCiclo}/salon/{salon}','HorarioController@modificarAsignacion');
		Route::get('modules/personal/horario/grupo/qr/{idGrupo}/carrera/{idCarrera}/ciclo/{idCiclo}/salon/{salon}','PersonalController@generarQRSalon');
		Route::get('modules/personal/horario/grupo/qr/{idGrupo}/carrera/{idCarrera}/ciclo/{idCiclo}/salon/{salon}/dia/{dia}','PersonalController@generarQRSalon');
		Route::post('modules/personal/horario/grupo/eliminar/horario','HorarioController@dropAsignacion');
		Route::post('modules/personal/horario/grupo/modificar/horario','HorarioController@saveModA');
		Route::get('modules/personal/horario/incidencias','HorarioController@incidencia');
		Route::post('modules/personal/horario/incidencias','HorarioController@saveIncidencia');
		Route::get('modules/personal/horario/incidencias/docente','HorarioController@incidenciaD');
		Route::post('modules/personal/horario/incidencias/docente/find','HorarioController@buscarAsignacion');
		Route::post('modules/personal/horario/incidencias/docente','HorarioController@insertIncidenciaD');
		Route::get('modules/personal/horario/logclases','HorarioController@checkLog');
		Route::post('modules/personal/horario/logclases','HorarioController@updateLog');
		Route::get('modules/personal/history','HorarioController@history');
		//BLOQUEOS DE CARGA
		Route::post('modules/personal/horario/bloqueo','HorarioController@setBloqueo');
		/**
		 * Modulo plateles
		 */
		Route::get('modules/personal/plantel','PlantelController@inicio');
		Route::get('modules/personal/plantel/add','PlantelController@add');
		Route::post('modules/personal/plantel/add/municipios','PlantelController@municipios');
		Route::post('modules/personal/plantel/add','PlantelController@save');
		Route::get('modules/personal/plantel/modify/{idPlantel}','PlantelController@modificar');
		Route::post('modules/personal/plantel/modify','PlantelController@saveMod');
		//end modulo planteles
		/**
		 * Modulo tipo empleado
		 */
		Route::get('modules/personal/tipoempleado','TipoEmpleadoController@inicio');
		Route::get('modules/personal/tipoempleado/add','TipoEmpleadoController@add');
		Route::post('modules/personal/tipoempleado/add','TipoEmpleadoController@save');
		Route::get('modules/personal/tipoempleado/modify/{id}','TipoEmpleadoController@modificar');
		Route::post('modules/personal/tipoempleado/modify','TipoEmpleadoController@saveMod');
		Route::post('modules/personal/tipoempleado/delete','TipoEmpleadoController@delete');
		/**
		 * Modulo puestos
		 */
		Route::get('modules/personal/puesto','PuestoController@inicio');
		Route::get('modules/personal/puesto/add','PuestoController@add');
		Route::post('modules/personal/puesto/add','PuestoController@save');
		Route::get('modules/personal/puesto/modify/{id}','PuestoController@modificar');
		Route::post('modules/personal/puesto/modify','PuestoController@saveMod');
		Route::post('modules/personal/puesto/delete','PuestoController@delete');
		/**
		 * Modulo Departamentos
		 */
		Route::get('modules/personal/departamento','DepartamentoController@inicio');
		Route::get('modules/personal/departamento/add','DepartamentoController@add');
		Route::post('modules/personal/departamento/add','DepartamentoController@save');
		Route::get('modules/personal/departamento/modify/{id}','DepartamentoController@modificar');
		Route::post('modules/personal/departamento/modify','DepartamentoController@saveMod');
		Route::post('modules/personal/departamento/delete','DepartamentoController@delete');
		/**
		 * reportes RH
		 */
		Route::get('modules/personal/reportes/docente/general/{modalidad}/plantel/{plantel}','ReportesController@general');
		Route::get('modules/personal/reportes','ReportesController@menu');
		Route::post('modules/personal/reportes/generar','ReportesController@generar');
		Route::post('modules/personal/reportes/generar/individual','ReportesController@generarIndividual');
		Route::post('modules/personal/reportes/generar/carga', 'ReportesController@generarCarga' );
		Route::post('modules/personal/reportes/validar/', 'ReportesController@validateCarga' );
//FIN DEL MODULO GENERAL RH

		/**
		 * Modulo general de Control escolar
		 */
		Route::get('modules/escolar','ControlController@inicio');

		/**
		 * modulo materias
		 */
		Route::get('modules/escolar/materia','MateriaController@inicio');
		Route::get('modules/escolar/materia/add','MateriaController@add');
		Route::post('modules/escolar/materia/add','MateriaController@save');
		Route::get('modules/escolar/materia/modify/{id}','MateriaController@modificar');
		Route::post('modules/escolar/materia/modify','MateriaController@saveMod');
		Route::post('modules/escolar/materia/delete','MateriaController@delete');
		//end modulo materia
		/**
		 * modulo Ciclos
		 */
		Route::get('modules/escolar/ciclos','CicloController@inicio');
		Route::get('modules/escolar/ciclos/add','CicloController@add');
		Route::post('modules/escolar/ciclos/add','CicloController@save');
		Route::get('modules/escolar/ciclos/modify/{id}','CicloController@modificar');
		Route::post('modules/escolar/ciclos/modify','CicloController@saveMod');
		Route::post('modules/escolar/ciclos/delete','CicloController@delete');
		Route::post('modules/escolar/ciclos/activo','CicloController@setActivo');
		/**
		 * Modulo grupos
		 */
		Route::get('modules/escolar/grupos','GruposController@inicio');
		Route::get('modules/escolar/grupos/add','GruposController@add');
		Route::post('modules/escolar/grupos/add','GruposController@save');
		Route::get('modules/escolar/grupos/modify/{id}','GruposController@modificar');
		Route::post('modules/escolar/grupos/modify','GruposController@saveMod');
		Route::post('modules/escolar/grupos/delete','GruposController@delete');
		Route::post('modules/personal/horario/grupo/grupos','GruposController@getGrupos');
		/**
		 *Modulo Carreras
		 */
		Route::get('modules/escolar/carrera','CarreraController@inicio');
		Route::get('modules/escolar/carrera/add','CarreraController@add');
		Route::post('modules/escolar/carrera/add','CarreraController@save');
		Route::get('modules/escolar/carrera/modify/{id}','CarreraController@modificar');
		Route::post('modules/escolar/carrera/modify','CarreraController@saveMod');
		Route::post('modules/escolar/carrera/delete','CarreraController@delete');
		Route::post('modules/personal/horario/grupo/carreras','CarreraController@getCarreras');

		/** Generar código QR */
		Route::get('modules/personal/qr/{id}', 'PersonalController@generarQR');
		Route::get('modules/personal/asistencia/{id}','PersonalController@asistenciaDocente');
	});


	/*Route::group(['middleware' => ['auth','administrativo']], function (){});*/


