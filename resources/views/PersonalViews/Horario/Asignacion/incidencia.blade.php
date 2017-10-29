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
            <li class="active">
                <a href="{{url('modules/personal/horario/incidencias')}}">
                    <i class="fa fa-circle-o"></i><span>Admistrativo</span>
                </a>
            </li>
            <li>
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
            Incidencias Administrativas.
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
                    <small>Administrativo</small>
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
                      action="{{ url('modules/personal/horario/incidencias') }}">
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
                    <div class="form-group {{ $errors->has('tipo') ? ' has-error' : '' }}">
                        <label for="tipo" class="col-sm-2 control-label">Tipo Incidencia</label>
                        <div class="col-sm-5">
                            <select id="tipo" name="tipo" class="select2 form-control input-sm" onchange="tipos()">
                                <option disabled selected>Selecciona un tipo</option>
                                <option value="1">Hora entrada</option>
                                <option value="2">Hora salida</option>
                                <option value="3">Hora Entrada - Salida</option>
                                <option value="4">Desayuno</option>
                            </select>
                            @if ($errors->has('tipo'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('tipo') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <!-- FECHA NACIMIENTO -->
                    <div class="form-group {{ $errors->has('fecha_inicio') ? ' has-error' : '' }}" id="fech">
                        <label for="fechaInicio" class="col-sm-2 control-label">Fecha</label>
                        <div class="col-sm-5 input-group date"
                             style="padding-left: 15px;padding-right: 15px;">

                            <input type="text" id="fechaInicio" name="fechaInicio"
                                   class="form-control input-sm" value="{{date("Y-m-d")}}" required/>
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('entrada') ? ' has-error' : '' }}" id="he">
                        <label for="entrada" class="col-sm-2 control-label">Hora Entrada</label>

                        <div class="col-sm-5 ">
                            <div class="input-group clockpicker" data-align="top"
                                 data-autoclose="true">
                                <input type="text" class="form-control" name="entrada" id="entrada">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            @if ($errors->has('entrada'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('entrada') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('salida') ? ' has-error' : '' }}" id="hs">
                        <label for="salida" class="col-sm-2 control-label">Hora Salida</label>
                        <div class="col-sm-5 ">
                            <div class="input-group clockpicker" data-align="top"
                                 data-autoclose="true">
                                <input type="text" class="form-control timepicker2" name="salida" id="salida">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            @if ($errors->has('salida'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('salida') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('motivo') ? ' has-error' : '' }}">
                        <label for="motivo" class="control-label col-sm-2">Motivo</label>
                        <div class="col-sm-5 input-group" style="padding-left: 15px;padding-right: 15px;">
                            <input type="text" class="form-control input-sm" name="motivo" id="motivo"
                                   value="{{ old('motivo') }}"/>
                            @if ($errors->has('motivo'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('motivo') }}</strong>
                                </p>
                            @endif
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
        function docente() {
            $("#act").val(1);
            $("#myModal").modal();
        }

        function generarAdmon() {
            $("#act").val(2);
            $("#myModal").modal();
        }

        function doReporte() {
            var tipoReporte = $("#act").val();
            var fechaInicio = $("#fechaInicio").val();
            var fechaFin = $("#fechaFin").val();

        }

        function tipos() {
            hiddentipos();
            var tipo = $("#tipo").val();
            switch (parseInt(tipo)) {
                case 1:
                    $("#he").show();
                    break;
                case 2:
                    $("#hs").show();
                    break;
                case 4:
                case 3:
                    $("#he").show();
                    $("#hs").show();
                    break;
                default:
                    break;
            }
        }

        function hiddentipos() {
            $("#he").hide();
            $("#hs").hide();
        }
        $(function () {
            hiddentipos();
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