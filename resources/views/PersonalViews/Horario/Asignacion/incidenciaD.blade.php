@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Incidencias")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/datepicker/datepicker3.css')}}">
    <!-- Bootstrap time Picker-->
    <link rel="stylesheet"
          href="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css')}}">
@endsection
@section('menuLateral')
    <li class="active treeview">
        <a href="#">
            <i class="fa fa-ticket"></i><span>Incidencias</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{url('modules/personal/horario/incidencias')}}">
                    <i class="fa fa-circle-o"></i><span>Admistrativo</span>
                </a>
            </li>
            <li class="active">
                <a href="{{url('modules/personal/horario/incidencias/docente')}}">
                    <i class="fa fa-circle-o"></i><span>Docente</span>
                </a>
            </li>

        </ul>

    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Incidencias Docentes.
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
                <h3 class="box-title">Incidencias
                    <small>Docente</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(session('mensaje'))
                    <div class="callout callout-success">
                        <p>{{session('mensaje')}}</p>
                    </div>
                @endif
                <form role="form" method="POST" class="form-horizontal"
                      action="{{ url('modules/personal/horario/incidencias/docente') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="empleado" class="col-sm-2 control-label">Empleado</label>
                        <div class="col-sm-5">
                            <select id="empleado" name="empleado" class="select2 form-control input-sm">
                                @foreach($empleados as $empleado)
                                    <option value={{$empleado->id}}>{{$empleado->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- FECHA INCIDENCIA -->
                    <div class="form-group {{ $errors->has('fechaInicio') ? ' has-error' : '' }}" id="fech">
                        <label for="fechaInicio" class="col-sm-2 control-label">Fecha</label>
                        <div class="col-sm-5 input-group date"
                             style="padding-left: 15px;padding-right: 15px;">
                            <input type="text" id="fechaInicio" name="fechaInicio"
                                   class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            @if ($errors->has('fechaInicio'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('asignacion') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-5">
                            <a class="btn btn-success center-block" onclick="getAsignacion()"><i class="fa fa-floppy-o"></i>
                                Buscar
                            </a>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('asignacion') ? ' has-error' : '' }}">
                        <label for="asignacion" class="col-sm-2 control-label">Asignación</label>
                        <div class="col-sm-5">
                            <select id="asignacion" name="asignacion" class="select2 form-control input-sm" >
                                <option disabled selected>Selecciona una asignación</option>
                            </select>
                            @if ($errors->has('asignacion'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('asignacion') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="motivo" class="col-sm-2 control-label">Motivo</label>
                        <div class="col-sm-5">
                            <input type="text" id="motivo" name="motivo" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary center-block"><i class="fa fa-floppy-o"></i>
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection
@section('js')
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('components/plugins/datepicker/bootstrap-datepicker.js')}}" charset="UTF-8"></script>
    <script src="{{ asset('components/plugins/datepicker/locales/bootstrap-datepicker.es.js')}}"
            charset="UTF-8"></script>
    <!-- bootstrap time picker-->
    <script src="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function hiddentipos() {
            $("#he").hide();
            $("#hs").hide();
        }

        /**
         * Metodo para buscar las asignaciones de horario para el docente
         * en la fecha solicitada
         */
        function getAsignacion() {
            //obtenemos las dos variables necesarias para hacer la consulta
            var idEmpleado = $("#empleado").val();
            var fecha = $("#fechaInicio").val();
            //hacemos el ajax
            $.ajax({
                type: "POST",
                url: '{{ url("modules/personal/horario/incidencias/docente/find") }}',
                data: {
                    idEmpleado: idEmpleado,
                    fecha: fecha,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                success: function (data, textStatus, jqXHR) {
                    $("#asignacion").empty();
                    var todo = "<option disabled selected>Selecciona una asignación</option>";
                    if(data.length > 0) { 
                        //recorremos el JSON
                        $.each(data,function(index, value){
                            horaEntrada = value.hora_entrada;
                            horaSalida = value.hora_salida;
                            asignacionHorario = value.id_asignacion_horario;
                            todo += "<option value='"+asignacionHorario+"'>"+horaEntrada+ " - " +horaSalida+"<option>";
                        });
                        
                    } else {
                        alert("No asiganciones para este día");
                    }
                    $("#asignacion").append(todo);
                }
            });
        }
        
        $(function () {
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
            $('.clockpicker').clockpicker();
        });
    </script>
@endsection