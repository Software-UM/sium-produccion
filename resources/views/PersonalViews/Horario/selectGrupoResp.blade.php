@extends('layout.principal')
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <style>
        .btn-app {
            min-width: 100%;
            margin: auto;
        }

        hr {
            display: block;
            position: relative;
            padding: 0;
            margin: 8px auto;
            height: 0;
            width: 100%;
            max-height: 0;
            font-size: 1px;
            line-height: 0;
            clear: both;
            border: none;
            border-top: 3px solid #3c8dbc;
            border-bottom: 1px solid #ffffff;
        }
    </style>
@endsection
@section('menuLateral')
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Carga Academica</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu active">
            <li ><a href="/modules/personal/horario/grupo"><i class="fa fa-circle-o"></i>Ver carga
                    Horarios</a>
            </li>
            <li class="active"><a href="/modules/personal/horario/grupo/seleccionar"><i class="fa fa-circle-o"></i>Asignación
                    Carga</a>
            </li>

        </ul>
    </li>

    <li>
        <a href="{{url('modules/personal/reportes')}}">
            <i class="fa fa-area-chart"></i><span>Reportes</span>
        </a>
    </li>
    <li class="treeview">
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
            Personal
            <small>Horario</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Personal</a></li>
            <li><a href="#">Horarios</a></li>
            <li><a href="#">Grupos</a></li>
            <li class="active">Seleccionar</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión de Horarios
                    <small>Seleccionar grupo</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    @if(session('mensaje'))
                        <div class="callout callout-danger">
                            <p>{{session('mensaje')}}</p>
                        </div>
                    @endif

                    <div class="col-sm-12">
                        <form class="form form-inline" method="post">
                            <!--action="url("/modules/personal/horario/grupo/seleccionar")"-->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="form-group {{ $errors->has('ciclo') ? ' has-error' : '' }}">
                                <select class="form-control input-sm" id="ciclo" name="ciclo">
                                    @foreach($ciclos as $ciclo)
                                        <option value="{{$ciclo->id}}"
                                                @if($ciclo->activo == 1) selected @endif>{{$ciclo->nombre}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('modalidad'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('modalidad') }}</strong>
                                    </p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('modalidad') ? ' has-error' : '' }}">
                                <select class="form-control" id="modalidad" name="modalidad"
                                        onchange="getCarreras(this)">
                                    <option disabled selected>Modalidad</option>
                                    <option value="1">ESCOLARIZADO</option>
                                    <option value="2">SEMIESCOLARIZADO</option>
                                </select>
                                @if ($errors->has('modalidad'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('modalidad') }}</strong>
                                    </p>
                                @endif

                            </div>
                            <div class="form-group {{ $errors->has('carrera') ? ' has-error' : '' }}">
                                <select class="form-control input-sm" id="carrera" name="carrera"
                                        onchange="getGrupos()">

                                </select>
                                @if ($errors->has('carrera'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('carrera') }}</strong>
                                    </p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('grupo') ? ' has-error' : '' }}">
                                <select class="form-control input-sm" id="grupo" name="grupo">

                                </select>
                                @if ($errors->has('grupo'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('grupo') }}</strong>
                                    </p>
                                @endif

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary center-block btn-flat"><i
                                            class="fa fa-floppy-o"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12">&nbsp;</div>
                    @if(session('data'))
                        <hr>
                        <div class="col-md-12">
                            <?php
                            $response = (object)Session::get('data');
                            $carrera = $response->carrera;
                            $idModalidad = 0;
                            ?>
                            <div class="col-sm-12">
                                <p class="text-center">
                                    <strong>{{$carrera->nombre}} @foreach($response->grupo as $gp) {{$gp->grado}} {{$gp->grupo}} @if($gp->id_modalidad == 1) {{"ESCOLARIZADO"}}
                                        @else {{"SEMIESCOLARIZADO"}} @endif @endforeach </strong>
                                </p>
                            </div>
                            <div class="col-sm-12">
                            @foreach($response->grupo as $gp)
                                @if($gp->id_modalidad == 1)
                                    <!--Tabla escolarizado-->
                                        <div class="table-responsive no-padding">
                                            <table class="table table-bordered">
                                                <thead class="bg-orange">
                                                <tr>
                                                    <th class="col-sm-1">Hora</th>
                                                    <th class="col-sm-2">Lunes</th>
                                                    <th class="col-sm-2">Martes</th>
                                                    <th class="col-sm-2">Miércoles</th>
                                                    <th class="col-sm-2">Jueves</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th>7:00 - 8:00</th>
                                                    <td>
                                                        <div id="b00">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(0,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div id="b01">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(0,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b02">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(0,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b03">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(0,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>

                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>8:00 - 9:00</th>
                                                    <td>
                                                        <div id="b10">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b11">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div id="b12">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b13">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>9:00 - 10:00</th>
                                                    <td>
                                                        <div id="b20">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b21">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b22">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b23">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>10:00 - 11:00</th>
                                                    <td>
                                                        <div id="b30">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b31">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b32">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b33">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>11:00 - 12:00</th>
                                                    <td>
                                                        <div id="b40">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b41">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b42">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b43">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>12:00 - 13:00</th>
                                                    <td>
                                                        <div id="b50">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b51">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b52">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b53">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                @else
                                    <!-- Tabla semiescolarizado -->
                                        <div class="table-responsive no-padding">
                                            <table class="table table-bordered">
                                                <thead class="bg-orange">
                                                <tr>
                                                    <th class="col-sm-1">Hora</th>
                                                    <th class="col-sm-3">Sábados</th>
                                                    <th class="col-sm-3">Domingos</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <th>8:00 - 9:00</th>
                                                    <td>
                                                        <div id="b15">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b16">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(1,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>9:00 - 10:00</th>
                                                    <td>
                                                        <div id="b25">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b26">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(2,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>10:00 - 11:00</th>
                                                    <td>
                                                        <div id="b35">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b36">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(3,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>11:00 - 12:00</th>
                                                    <td>
                                                        <div id="b45">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b46">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(4,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>12:00 - 13:00</th>
                                                    <td>
                                                        <div id="b55">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b56">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(5,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>13:00 - 14:00</th>
                                                    <td>
                                                        <div id="b65">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b66">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>14:00 - 15:00</th>
                                                    <td>
                                                        <div id="b75">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,5)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b76">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,6)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                @endforeach

                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- Modal view -->
    <div class="modal fade" id="verEmpleado" role="dialog">
        <div class="modal-dialog">
            <!-- Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Asignar Horario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form form-horizontal">
                                @if(session('data'))
                                    <input type="hidden" name="idGrupo" id="idGrupo"
                                           value=@foreach($response->grupo as $gp) {{$gp->id}}@endforeach>
                                    <input type="hidden" name="idciclo" id="idciclo" value={{$response->ciclo}}>
                                    <input type="hidden" name="idCarrera" id="idCarrera" value={{$carrera->id}}>
                                    <input type="hidden" name="dia" id="dia">
                                    <input type="hidden" name="hora" id="hora">
                                @endif
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Docente:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" name="empleado"
                                                id="empleado">
                                            <option disabled selected>SELECCIONA UN DOCENTE</option>
                                            @if(session('data'))
                                                @foreach($response->empleados as $empleado)
                                                    <option value="{{$empleado->id}}">{{$empleado->nombre}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Materia:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" name="materia"
                                                id="materia">
                                            <option disabled selected>SELECCIONA UNA MATERIA</option>
                                            @if(session('data'))
                                                @foreach($response->materia as $mat)
                                                    <option value="{{$mat->clave}}">{{$mat->nombre}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Numero de horas:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="horas" id="horas">
                                            <option value="0" selected>1</option>
                                            <option value="1">2</option>
                                            <option value="2">3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-flat bg-olive pull-right"
                                                onclick="doAsignacion()"><i
                                                    class="fa fa-floppy-o"></i> Guardar
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <!-- validator -->
    <script src="{{asset('components/plugins/validator/validator.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var dia = ['lunes', 'martes', 'miercoles', 'jueves'];
        var horas = ['7:00 - 8:00', '8:00 - 9:00', '9:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00'];
        function asignar(fila, columna) {
            $("#dia").val(columna);
            $("#hora").val(fila);
            $("#verEmpleado").modal();
        }

        function doAsignacion() {
            var dia = $("#dia").val();
            var hora = $("#hora").val();
            var ciclo = $("#idciclo").val();
            var idEmpleado = $("#empleado").val();
            var idMateria = $("#materia").val();
            var horas = $("#horas").val();
            var idGrupo = $("#idGrupo").val();
            var idCarrera = $("#idCarrera").val();
            //var idGrupo =
            if (idEmpleado == null || idMateria == null) {
                alert("Favor de llenar los campos");
            } else {
                var nombreEmpleado = $("#empleado option:selected").text();
                var nombreMateria = $("#materia option:selected").text();
                //validar(idEmpleado, idMateria, horas, idGrupo, dia, hora, ciclo, idCarrera);

                var dfd = new jQuery.Deferred();
                $.when(validar(idEmpleado, idMateria, horas, idGrupo, dia,
                        hora, ciclo, idCarrera)).done(function (data, textStatus, jqXHR) {

                    if (data.success){
                        alert(data.success);
                        var htmls = "<div class='bg-green disabled color-palette' style='color: #0c0c0c'>"+
                                "<span>"+nombreEmpleado+"<br>"+nombreMateria+"</span></div>";
                        var htmls2 = "<div class='bg-blue disabled color-palette' style='color: #0c0c0c'>"+
                                "<span>"+nombreEmpleado+"<br>"+nombreMateria+"</span></div>";
                        var html3 = "<div class='bg-red disabled color-palette' style='color: #0c0c0c'>"+
                                "<span>"+nombreEmpleado+"<br>"+nombreMateria+"</span></div>";
                        var hora2 = parseInt(hora)+1;
                        var hora3 = parseInt(hora)+2;
                        switch (parseInt(horas)){
                            case 0:
                                $("#b"+hora+""+dia).empty();
                                $("#b"+hora+""+dia).append(htmls);
                                break;
                            case 1:
                                $("#b"+hora+""+dia).empty();
                                $("#b"+hora+""+dia).append(htmls2);
                                $("#b"+hora2+""+dia).empty();
                                $("#b"+hora2+""+dia).append(htmls2);
                                break;
                            case 2:
                                $("#b"+hora+""+dia).empty();
                                $("#b"+hora+""+dia).append(html3);
                                $("#b"+hora2+""+dia).empty();
                                $("#b"+hora2+""+dia).append(html3);
                                $("#b"+hora3+""+dia).empty();
                                $("#b"+hora3+""+dia).append(html3);
                                break;
                        }

                        $("#verEmpleado").modal("hide");
                    }else{
                        alert(data.error);
                    }
                    //console.log();
                });

            }

        }
        function getCarreras(combo) {
            var mod = $(combo).val();
            var rpost = $.post("carreras", {
                id: mod,
            });
            $("#carrera").empty();
            rpost.success(function (result) {
                var optionCarrera = "<option disabled selected>Selecciona una carrera</option>";
                result.forEach(function (entry) {
                    optionCarrera += "<option value='" + entry['id'] + "'>" + entry['nombre'] + "</option>";
                });
                $("#carrera").append(optionCarrera);
            });
            rpost.error(function (result, status, ss) {
                alert("Error" + result.responseText);
            });
        }

        function getGrupos() {
            var idCarrea = $("#carrera").val();
            var modalidad = $("#modalidad").val();
            var rpost = $.post("grupos", {
                idmodalida: modalidad,
                idcarrera: idCarrea,
            });
            $("#grupo").empty();
            rpost.success(function (result) {
                var optionCarrera = "<option disabled selected>Selecciona un grupo</option>";
                result.forEach(function (entry) {
                    optionCarrera += "<option value='" + entry['id'] + "'>" + entry['grado'] + " " + entry['grupo'] + "</option>";
                });
                $("#grupo").append(optionCarrera);
            });
            rpost.error(function (result, status, ss) {
                alert("Error" + result.responseText);
            });
        }
        $(function () {
            $(".select2").select2();
        });
    </script>
@endsection