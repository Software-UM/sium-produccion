@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/datepicker/datepicker3.css')}}">
    <style type="text/css">
        .select2{
            width: 100% !important;
        }
    </style>
@endsection
@section('menuLateral')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Carga Academica</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal/horario/grupo')}}"><i class="fa fa-circle-o"></i>Ver carga
                    Horarios</a>
            </li>
            <li><a href="{{ url('modules/personal/horario/grupo/seleccionar')}}"><i class="fa fa-circle-o"></i>Asignación
                    Carga</a>
            </li>

        </ul>
    </li>

    <li class="active">
        <a href="{{url('modules/personal/reportes')}}">
            <i class="fa fa-area-chart"></i><span>Reportes</span>
        </a>
    </li>

@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Selecciona un reporte
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Personal</a></li>
            <li class="active">Reportes</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Reporte de Horarios
                    <small>Seleccionar Tipo Reporte</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(session('mensaje'))
                    <div class="callout callout-danger">
                        <p>{{session('mensaje')}}</p>
                    </div>
                @endif
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Carga</h3>
                            <p>Académica</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file-pdf-o"></i>
                        </div>
                        <a href="#" onclick="generaCarga()" class="small-box-footer">
                            Generar <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2 ||  Auth::user()->tipo == 3)
                    @if(Auth::user()->id != 9)
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Asistencia</h3>
                                    <p>Docente</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="docente()" class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Asistencia</h3>
                                    <p>Administrativa</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="generarAdmon()" class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Asistencia</h3>
                                    <p>Becarios</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="generarBecarios()" class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>Horas</h3>

                                <p>Proyección</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-pdf-o"></i>
                            </div>
                            <a href="{{ url('modules/personal/reportes/docente/general/1/plantel/'.$cct_plantel) }}" target="_blank"
                               class="small-box-footer">
                                Generar <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    @if(Auth::user()->id != 9)
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Desayuno</h3>
                                    <p>&nbsp;</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="generarDesayuno()"
                                   class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Asistencia</h3>
                                    <p>Individual</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="generaIndividual()" class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Asistencia</h3>
                                    <p>General de Docentes</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-pdf-o"></i>
                                </div>
                                <a href="#" onclick="generaAsisDoc()" class="small-box-footer">
                                    Generar <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- Modal Carga Académica -->
    <div class="modal fade" id="modalCarga" role="">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Carga Académica</h4>
                </div>
                <form role="form" method="POST" class="form form-horizontal"
                      action="{{url("/modules/personal/reportes/generar/carga")}}" target="_blank">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-warning" id="alertDoc"></div>
                                <p>SELECCIONA AL DOCENTE</p>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="form-group">
                                    <label for="empleadoC" class="col-sm-3 control-label">Nombre Empleado:</label>
                                    <div class="col-sm-12">
                                        <select id="empleadoC" name="empleadoC" class="select2" onchange="validateCarga(this)">
                                            @foreach($docentes as $docente)
                                                <option value="{{$docente->id}}">{{$docente->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ciclo" class="col-sm-3 control-label">Ciclo:</label>
                                    <div class="col-sm-12">
                                        <select class="form-control input-sm" id="ciclo" name="ciclo" onchange="validateCarga(this)">
                                            @foreach($ciclos as $ciclo)
                                                <option value="{{$ciclo->id}}"
                                                    @if($ciclo->activo == 1) selected @endif>{{$ciclo->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="cargaDoc">Aceptar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Configurar Parámetros</h4>
                </div>
                <form role="form" method="POST" class="form form-horizontal"
                      action="{{url("/modules/personal/reportes/generar")}}" target="_blank">
                    <div class="modal-body">
                        <p>LLENA LOS CAMPOS</p>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <!-- FECHA NACIMIENTO -->
                        <div class="form-group">
                            <label for="fechaInicio" class="col-sm-3 control-label">Fecha Inicio</label>
                            <div class="col-sm-8 input-group date"
                                 style="padding-left: 15px;padding-right: 15px;">

                                <input type="text" id="fechaInicio" name="fechaInicio"
                                       class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaFin" class="col-sm-3 control-label">Fecha Fin</label>
                            <div class="col-sm-8 input-group date"
                                 style="padding-left: 15px;padding-right: 15px;">

                                <input type="text" id="fechaFin" name="fechaFin"
                                       class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="act" id="act" hidden>
                        <div id="extra"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Aceptar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal Individual -->
    <div class="modal fade" id="modalIndividual" role="">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reporte individual</h4>
                </div>
                <form role="form" method="POST" class="form form-horizontal"
                      action="{{url("/modules/personal/reportes/generar/individual")}}" target="_blank">
                    <div class="modal-body">
                        <p>LLENA LOS CAMPOS</p>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group">
                            <label for="empleado" class="col-sm-3 control-label">Nombre Empleado:</label>
                            <div class="col-sm-8">
                                <select id="empleado" name="empleado" class="select2">
                                    @foreach($empleados as $empleado)
                                        <option value="{{$empleado->id}}">{{$empleado->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- FECHA NACIMIENTO -->
                        <div class="form-group">
                            <label for="fechaInicio1" class="col-sm-3 control-label">Fecha Inicio</label>
                            <div class="col-sm-8 input-group date"
                                 style="padding-left: 15px;padding-right: 15px;">
                                <input type="text" id="fechaInicio1" name="fechaInicio1"
                                       class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaFin1" class="col-sm-3 control-label">Fecha Fin</label>
                            <div class="col-sm-8 input-group date"
                                 style="padding-left: 15px;padding-right: 15px;">

                                <input type="text" id="fechaFin1" name="fechaFin1"
                                       class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="act" id="act" hidden>
                        <div id="extra"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Aceptar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
@section('js')
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('components/plugins/datepicker/bootstrap-datepicker.js')}}" charset="UTF-8"></script>
    <script src="{{ asset('components/plugins/datepicker/locales/bootstrap-datepicker.es.js')}}"
            charset="UTF-8"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function docente() {
            $("#act").val(1);
            $("#myModal").modal();
        }

        function generarAdmon() {
            $("#act").val(2);
            $("#myModal").modal();
        }

        function generarDesayuno() {
            $("#act").val(3);
            $("#myModal").modal();
        }

        function generaAsisDoc() {
            $("#act").val(4);
            $("#myModal").modal();
        }

        function generarBecarios() {
            $("#act").val(5);
            $("#myModal").modal();
        }

        function generaIndividual() {
            $("#modalIndividual").modal();
        }

        function generaCarga() {
            $("#modalCarga").modal();
        }

        function doReporte() {
            var tipoReporte = $("#act").val();
            var fechaInicio = $("#fechaInicio").val();
            var fechaFin = $("#fechaFin").val();

        }

        function validateCarga(){
            var idEmpleado = $("#empleadoC").val();
            var idCiclo = $("#ciclo").val();
            $("#alertDoc").empty();
            $("#alertDoc").hide();
            $.ajax({
                type: "POST",
                url: "{{url('/modules/personal/reportes/validar')}}",
                data: {
                    idEmpleado: idEmpleado,
                    idCiclo: idCiclo
                },
                dataType: "html",
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                success: function (data, textStatus, jqXHR) {
                    var resp = parseInt(data);
                    if (resp == 1) {
                        $("#cargaDoc").prop("disabled", false);
                    }else {
                        $("#cargaDoc").prop( "disabled", true );
                        $("#alertDoc").append("No cuenta con un horario asignado");
                        $("#alertDoc").show();
                    }
                }
            });
        }
        $(function () {
            $("#alertDoc").empty();
            $("#alertDoc").hide();

            $(".select2").select2();
            //Date picker

            $('#fechaInicio').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',

            });
            $('#fechaFin').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',

            });

            $('#fechaInicio1').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',

            });
            $('#fechaFin1').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',

            });

        });
    </script>
@endsection