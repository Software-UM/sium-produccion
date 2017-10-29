@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('components/plugins/iCheck/all.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/datepicker/datepicker3.css')}}">
    <!-- Bootstrap time Picker-->
    <link rel="stylesheet"
          href="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css')}}">
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

        .results tr[visible='false'],
        .no-result {
            display: none;
        }

        .results tr[visible='true'] {
            display: table-row;
        }

        .counter {
            padding: 8px;
            color: #ccc;
        }

        .dataTables_filter {
            display: none;
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
            <li ><a href="{{ url("modules/personal/horario/grupo") }}"><i class="fa fa-circle-o"></i>Ver carga
                    Horarios</a>
            </li>
            <li class="active"><a href="{{ url("modules/personal/horario/grupo/seleccionar")}}"><i class="fa fa-circle-o"></i>Asignación
                    Carga</a>
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
                                    <option value="3">POSGRADO</option>
                                    <option value="4">LÍNEA</option>
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
                                        @elseif($gp->id_modalidad == 2) {{"SEMIESCOLARIZADO"}} @elseif($gp->id_modalidad == 3) {{"POSGRADO"}} @elseif($gp->id_modalidad == 4){{"POSGRADO EN LÍNEA"}}@endif @endforeach </strong>
                                </p>
                            </div>
                            @include('PersonalViews.Horario.modalEdificio')
                            @include('PersonalViews.Horario.modaEdificioModificar')
                            <div class="col-sm-12">
                                <div class="col-lg-5 col-lg-offset-2">
                                    <p class="pull-right"><strong>Edficio:</strong><span id="edif"></span>
                                        <strong>Salón:</strong><span id="sal"></span></p>
                                </div>
                                <div class="cols-lg-7">
                                    <input type="hidden" id="salonElegido" value="43">
                                    <!--<button type="button" class="btn btn-info pull-right btn-lg" onclick="showModEdif()">Modificar Salón</button>-->
                                </div>


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
                                                <tr>
                                                    <th>13:00 - 14:00</th>
                                                    <td>
                                                        <div id="b60">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b61">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b62">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b63">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(6,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>14:00 - 15:00</th>
                                                    <td>
                                                        <div id="b70">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,0)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b71">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,1)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b72">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,2)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="b73">
                                                            <a class="btn btn-app btn-flat" onclick="asignar(7,3)">
                                                                <i class="fa fa-plus-square-o fa-5x"
                                                                   style="font-size: 2.5em"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                @elseif($gp->id_modalidad == 2)
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
                                @elseif($gp->id_modalidad == 3)
                                    <!-- Tabla posgrado-->
                                    @include('layout.tablaposgrado')
                                    @include('PersonalViews.Horario.tablaFechas')
                                @elseif($gp->id_modalidad == 4)
                                    <!-- Tabla modalidad en línea -->
                                        @include('layout.tablalinea')
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
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('components/plugins/iCheck/icheck.min.js') }}"></script>
    @if(session('data'))
        @if($gp->id_modalidad == 3 || $gp->id_modalidad == 4)
            <!-- iCheck 1.0.1 -->
            <script src="{{ asset('components/plugins/iCheck/icheck.min.js') }}"></script>
            <!-- bootstrap datepicker -->
            <script src="{{ asset('components/plugins/datepicker/bootstrap-datepicker.js')}}" charset="UTF-8"></script>
            <script src="{{ asset('components/plugins/datepicker/locales/bootstrap-datepicker.es.js')}}"
                    charset="UTF-8"></script>
            <!-- bootstrap time picker-->
            <script src="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
            <script src="{{ asset('components/plugins/datatables/jquery.dataTables.min.js')}}"></script>
            <script src="{{ asset('components/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
        @endif
    @endif
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
        @if(session('data'))
        @if($gp->id_modalidad == 3 || $gp->id_modalidad == 4)
        function doAsignacionP() {
            var ciclo = $("#idciclop").val();
            var idGrupo = $("#idGrupop").val();
            var idCarrera = $("#idCarrerap").val();
            var idEmpleado = $("#empleadop").val();
            var idMateria = $("#materiap").val();
            var horaEntrada = $("#entrada").val();
            var horaSalida = $("#salida").val();
            if (idEmpleado == null || idMateria == null || horaEntrada == null || horaSalida == "" || horaSalida == null || horaEntrada == "") {
                swal(
                    'Atención!',
                    "Favor de llenar todos los campos",
                    'warning'
                );
            } else {
                //hacemos el guardado de la asignacion
                $("#nMateriap").empty();
                $("#nNombrep").empty();
                //obtenemos el nombre del empleado y la materia seleccionada
                var nombreEmpleado = $("#empleadop option:selected").text();
                var nombreMateria = $("#materiap option:selected").text();
                $("#nMateriap").append(nombreMateria);
                $("#nNombrep").append(nombreEmpleado);
                $("#asignarFecha").modal({backdrop: 'static', keyboard: false});
            }

        }
        function doAsignarFecha() {
            //obtenemos las variables
            var fecha = $("#fechaClase").val();
            var d = new Date(fecha);
            var ciclo = $("#idciclop").val();
            var idGrupo = $("#idGrupop").val();
            var idCarrera = $("#idCarrerap").val();
            var idEmpleado = $("#empleadop").val();
            var idMateria = $("#materiap").val();
            var horaEntrada = $("#entrada").val();
            var horaSalida = $("#salida").val();
            var fechaClase = $("#fechaClase").val();
            var salon = $("#salonElegido").val();
            $.ajax({
                type: "POST",
                url: "{{ url("modules/personal/horario/grupo/validarp") }}",
                data: {
                    dia: d.getDay(),
                    idMateria: idMateria,
                    idEmpleado: idEmpleado,
                    horaEntrada: horaEntrada,
                    idGrupo: idGrupo,
                    horaSalida: horaSalida,
                    ciclo: ciclo,
                    idCarrera: idCarrera,
                    fechaClase: fechaClase,
                    salon: salon
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    if (data.success) {
                        swal(
                            'Correcto!',
                            data.success,
                            'success'
                        );
                        var dataTable = $('#tablaFechas').DataTable();
                        dataTable.row.add([
                            fechaClase,
                            "<a class='btn btn-danger btn-xs' onclick='quitarFecha(" + data.id_fecha + ",this,1)'><i class=\"fa fa-trash fa-lg\"></i> Eliminar</a>",
                        ]).draw(false);
                    } else {
                        swal('Atención!', data.error, 'warning');
                    }

                }
            });
        }

        function doAsignarFechaMod() {
            //obtenemos las variables
            /*$("#cicloMod").val(ciclo);
            $("#idGrupoMod").val(idGrupo);
            $("#idCarreraMod").val(idCarrera);
            $("#idEmpleadoMod").val(idEmpleado);
            $("#idMateriaMod").val(idMateria);*/
            var fecha = $("#fechaClaseMod").val();
            var d = new Date(fecha);
            var ciclo = $("#cicloMod").val();
            var idGrupo = $("#idGrupoMod").val();
            var idCarrera = $("#idCarreraMod").val();
            var idEmpleado = $("#idEmpleadoMod").val();
            var idMateria = $("#idMateriaMod").val();
            var horaEntrada = $("#horaEntradaMod").val();
            var horaSalida = $("#horaSalidaMod").val();
            var fechaClase = $("#fechaClaseMod").val();
            var salon = $("#salonElegido").val();
            $.ajax({
                type: "POST",
                url: "{{url("modules/personal/horario/grupo/validarp")}}",
                data: {
                    dia: d.getDay(),
                    idMateria: idMateria,
                    idEmpleado: idEmpleado,
                    horaEntrada: horaEntrada,
                    idGrupo: idGrupo,
                    horaSalida: horaSalida,
                    ciclo: ciclo,
                    idCarrera: idCarrera,
                    fechaClase: fechaClase,
                    salon: salon
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    if (data.success) {
                        swal(
                            'Correcto!',
                            data.success,
                            'success'
                        );
                        var dataTable = $('#tablaFechasMod').DataTable();
                        dataTable.row.add([
                            fechaClase,
                            "<a class='btn btn-danger btn-xs' onclick='quitarFecha(" + data.id_fecha + ",this,2)'><i class=\"fa fa-trash fa-lg\"></i> Eliminar</a>",
                        ]).draw(false);
                    } else {
                        swal('Atención!', data.error, 'warning');
                    }

                }
            });
        }

        function quitarFecha(id, row,tipo) {
            $.ajax({
                type: "DELETE",
                url: "{{url('modules/personal/horario/fechas')}}",
                data: {
                    idFecha: id,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    if (data.success) {
                        swal('Correcto!', data.success, 'success');
                        var i = row.parentNode.parentNode.rowIndex;
                        if(tipo == 1){
                            document.getElementById("tablaFechas").deleteRow(i);
                        }else{
                            document.getElementById("tablaFechasMod").deleteRow(i);
                        }

                    } else {
                        swal('Atención!', data.error, 'error');
                    }

                }
            });
        }

        function sendTablep() {
            //obtenemos el nombre del empleado y la materia seleccionada
            var nombreEmpleado = $("#empleadop option:selected").text();
            var nombreMateria = $("#materiap option:selected").text();
            var ciclo = $("#idciclop").val();
            var idGrupo = $("#idGrupop").val();
            var idCarrera = $("#idCarrerap").val();
            var idEmpleado = $("#empleadop").val();
            var idMateria = $("#materiap").val();
            var horaEntrada = $("#entrada").val();
            var horaSalida = $("#salida").val();
            var table = $('#tablaFechas').DataTable();
            table.clear().draw();
            var dataTable = $('#tablaAsig').DataTable();
             dataTable.row.add([
             nombreEmpleado,
             nombreMateria,
                '<a class="btn btn-danger btn-xs" title="Eliminar Asignación" onclick=""><i class="fa fa-trash"></i></a>'+
                '<a class="btn btn-success btn-xs" title="Ver Fechas" onclick="openTablaFecha(\''+nombreEmpleado
                +'\',\''+nombreMateria+'\','+ciclo+','+idGrupo+','+idCarrera+','+idEmpleado+',\''+idMateria+'\',\''+horaEntrada+'\',\''+horaSalida+'\')"><i class="fa fa-eye"></i></a>'
             ]).draw(false);
            $("#entrada").val("");
            $("#salida").val("");
            $("#asignarFecha").modal("hide");
        }

        function openTablaFecha(nombreEmpleado,nombreMateria,ciclo,idGrupo,idCarrera,idEmpleado,idMateria,horaEntrada, horaSalida){
            $("#cicloMod").val(ciclo);
            $("#idGrupoMod").val(idGrupo);
            $("#idCarreraMod").val(idCarrera);
            $("#idEmpleadoMod").val(idEmpleado);
            $("#idMateriaMod").val(idMateria);
            $("#horaEntradaMod").val(horaEntrada);
            $("#horaSalidaMod").val(horaSalida);
            $("#nMateriapMod").empty();
            $("#nNombrepMod").empty();
            $("#nMateriapMod").append(nombreMateria);
            $("#nNombrepMod").append(nombreEmpleado);
            var dataTable = $('#tablaFechasMod').DataTable();
            dataTable.clear().draw();
            /**
             * Obtenemos las fechas del docente
             */
            $.ajax({
                type: 'GET',
                url: "{{url('modules/personal/horario/fechas')}}",
                data: {
                    idGrupo: idGrupo,
                    ciclo: ciclo,
                    idCarrera: idCarrera,
                    idEmpleado: idEmpleado,
                    idMateria: idMateria
                },
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    if (data["error"]){
                        swal(
                            'Ups! Error!',
                            data.error,
                            'error'
                        );
                    }else{
                        console.log(data);
                        $.each(data, function (key, value) {
                            dataTable.row.add([
                                value["fecha"],
                                "<a class='btn btn-danger btn-xs' onclick='quitarFecha(" + value["idFechas"] + ",this,2)'><i class=\"fa fa-trash fa-lg\"></i> Eliminar</a>",
                            ]).draw(false);
                        });

                    }
                }
            });
            $("#asignarFechaMod").modal();
        }

        function updateTableP() {
            var ciclo = $("#idciclop").val();
            var idGrupo = $("#idGrupop").val();
            var idCarrera = $("#idCarrerap").val();
            var dataTable = $('#tablaAsig').DataTable();
            table.clear().draw();
            $.ajax({
                type: 'GET',
                url: "{{url('modules/personal/horario/asignacionp')}}",
                data: {
                    idGrupo: idGrupo,
                    ciclo: ciclo,
                    idCarrera: idCarrera,
                },
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {

                }
            });
        }
        @endif
        @endif
        function doAsignacion() {
            var dia = $("#dia").val();
            var hora = $("#hora").val();
            var ciclo = $("#idciclo").val();
            var idEmpleado = $("#empleado").val();
            var idMateria = $("#materia").val();
            var horas = $("#horas").val();
            var idGrupo = $("#idGrupo").val();
            var idCarrera = $("#idCarrera").val();
            var salon = $("#salonElegido").val();
            //var idGrupo =
            if (idEmpleado == null || idMateria == null) {
                swal(
                    'Atención!',
                    "Favor de llenar todos los campos",
                    'warning'
                );
            } else {
                var nombreEmpleado = $("#empleado option:selected").text();
                var nombreMateria = $("#materia option:selected").text();
                //validar(idEmpleado, idMateria, horas, idGrupo, dia, hora, ciclo, idCarrera);

                var dfd = new jQuery.Deferred();
                var url = "{{ url('modules/personal/horario/grupo/validar')}}";
                $.when(validar(idEmpleado, idMateria, horas, idGrupo, dia,
                    hora, ciclo, idCarrera, salon,url)).done(function (data, textStatus, jqXHR) {

                    if (data.success) {
                        swal(
                            'Correcto!',
                            data.success,
                            'success'
                        );
                        var htmls = "<div class='bg-green disabled color-palette' style='color: #0c0c0c'>" +
                            "<span>" + nombreEmpleado + "<br>" + nombreMateria + "</span></div>";
                        var htmls2 = "<div class='bg-blue disabled color-palette' style='color: #0c0c0c'>" +
                            "<span>" + nombreEmpleado + "<br>" + nombreMateria + "</span></div>";
                        var html3 = "<div class='bg-red disabled color-palette' style='color: #0c0c0c'>" +
                            "<span>" + nombreEmpleado + "<br>" + nombreMateria + "</span></div>";
                        var hora2 = parseInt(hora) + 1;
                        var hora3 = parseInt(hora) + 2;
                        switch (parseInt(horas)) {
                            case 0:
                                $("#b" + hora + "" + dia).empty();
                                $("#b" + hora + "" + dia).append(htmls);
                                break;
                            case 1:
                                $("#b" + hora + "" + dia).empty();
                                $("#b" + hora + "" + dia).append(htmls2);
                                $("#b" + hora2 + "" + dia).empty();
                                $("#b" + hora2 + "" + dia).append(htmls2);
                                break;
                            case 2:
                                $("#b" + hora + "" + dia).empty();
                                $("#b" + hora + "" + dia).append(html3);
                                $("#b" + hora2 + "" + dia).empty();
                                $("#b" + hora2 + "" + dia).append(html3);
                                $("#b" + hora3 + "" + dia).empty();
                                $("#b" + hora3 + "" + dia).append(html3);
                                break;
                        }

                        $("#verEmpleado").modal("hide");
                    } else {
                        swal(
                            'Atención!',
                            data.error,
                            'warning'
                        );
                    }
                    //console.log();
                });

            }

        }
        function getCarreras(combo) {
            var mod = $(combo).val();
            var rpost = $.post("{{url('modules/personal/horario/grupo/carreras')}}", {
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
            var rpost = $.post("{{url("modules/personal/horario/grupo/grupos")}}", {
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

        function buscarSalon() {
            idEdificio = $("#edificio").val();
            $("#salones").empty();
            $.ajax({
                type: "GET",
                url: "{{url('modules/personal/horario/grupo/salones')}}",
                data: {
                    id: idEdificio,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    //console.log(data);
                    $("#salones").append("<option disabled selected>SELECCIONA UNA SALÓN</option>");
                    $.each(data, function (key, value) {
                        $("#salones").append("<option value=" + value["id"] + ">" + value["numero"] + "</option>");
                    });
                }
            });
        }
        function setEdificios() {
            var idSalon = $("#salones").val();
            if (idSalon == null) {
                swal(
                    'Atención!',
                    'Favor de llenar todos los campos',
                    'warning'
                );
            } else {
                var ciclo = $("#idciclo").val();
                var idGrupo = $("#idGrupo").val();
                $.ajax({
                    type: "GET",
                    url: "{{url('modules/personal/horario/grupo/salones/validar')}}",
                    data: {
                        idSalon: idSalon,
                        ciclo: ciclo,
                        idGrupo: idGrupo
                    },
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal(
                            'Ups! Error!',
                            errorThrown,
                            'error'
                        );

                    },
                    success: function (data, textStatus, jqXHR) {
                        //console.log(data);
                        if (data["success"]) {
                            var salon = $('#salones option:selected').text();
                            var edificio = $('#edificio option:selected').text();
                            $("#edif").empty();
                            $("#sal").empty();
                            $("#salones").empty();
                            $("#edif").append(edificio);
                            $("#sal").append(salon);
                            $("#salonElegido").val(idSalon);
                            $("#modalEdifio").modal("hide");
                        } else {
                            swal(
                                'Atención!',
                                'Salón no disponible',
                                'warning'
                            );
                        }
                    }
                });

            }
        }

        function closeModal() {
            location.reload(true);
        }
        function closeModal2() {
            $("#modalEdificiom").modal("hide");
        }
        function showModEdif() {
            $("#modalEdificiom").modal();
        }
        function buscarSalon2() {
            idEdificio = $("#edificiom").val();
            $("#salonesm").empty();
            $.ajax({
                type: "GET",
                url: "{{url('modules/personal/horario/grupo/salones')}}",
                data: {
                    id: idEdificio,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                        'Ups! Error!',
                        errorThrown,
                        'error'
                    );

                },
                success: function (data, textStatus, jqXHR) {
                    //console.log(data);
                    $("#salonesm").append("<option disabled selected>SELECCIONA UNA SALÓN</option>");
                    $.each(data, function (key, value) {
                        $("#salonesm").append("<option value=" + value["id"] + ">" + value["numero"] + "</option>");
                    });
                }
            });
        }
        function modEdificios() {
            var idSalon = $("#salonesm").val();
            var salonOld = $("#salonElegido").val();
            if (idSalon == null) {
                swal(
                    'Atención!',
                    'Favor de llenar todos los campos',
                    'warning'
                );
            } else {
                var ciclo = $("#idciclo").val();
                var idGrupo = $("#idGrupo").val();
                $.ajax({
                    type: "POST",
                    url: "{{url('modules/personal/horario/grupo/salones')}}",
                    data: {
                        idSalon: salonOld,
                        ciclo: ciclo,
                        idGrupo: idGrupo,
                        idSalonNuevo: idSalon
                    },
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal(
                            'Ups! Error!',
                            errorThrown,
                            'error'
                        );

                    },
                    success: function (data, textStatus, jqXHR) {
                        //console.log(data);
                        if (data["success"]) {
                            var salon = $('#salonesm option:selected').text();
                            var edificio = $('#edificiom option:selected').text();
                            $("#edif").empty();
                            $("#sal").empty();
                            $("#salones").empty();
                            $("#edif").append(edificio);
                            $("#sal").append(salon);
                            $("#salonElegido").val(idSalon);
                            $("#modalEdificiom").modal("hide");
                        } else {
                            swal(
                                'Atención!',
                                'Salón no disponible',
                                'warning'
                            );
                        }
                    }
                });

            }
        }
        $(function () {

            $(".select2").select2();
            @if(session('data'))
            //$("#modalEdifio").modal();
            @if($gp->id_modalidad == 3 || $gp->id_modalidad == 4)
                $('.clockpicker').clockpicker();
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            //Date picker
            $('#fechaClase').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',
                minDate: -1
            });
            //Date picker
            $('#fechaClaseMod').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es',
                minDate: -1
            });
            var dataTable = $('#tablaFechas').dataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "Datos no encotrados en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Show _MENU_ entries",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No hay resultados",
                    "paginate": {
                        "first": "Primer",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
            $("#searchbox").keyup(function () {
                dataTable.fnFilter(this.value);
            });
            $("#tablaFechas_filter").addClass("pull-right");
            $("#tablaFechas_paginate").addClass("pull-right");
            @endif
            @endif
        });


    </script>
@endsection