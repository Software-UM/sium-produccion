@extends('layout.principal')
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style>
        .btn-app {
            min-width: 100%;
            margin: auto;
        }
    </style>
@endsection
@section('menuLateral')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal') }}"><i class="fa fa-arrow-circle-right"></i> Gestión del Personal</a></li>
        </ul>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu active">
            <li><a href="{{ url('modules/personal/horario') }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li ><a href="{{ url('modules/personal/horario/tipo') }}"><i class="fa fa-circle-o"></i> Tipo Horario</a></li>
            <li class="active"><a href="{{ url('modules/personal/horario/grupo') }}"><i class="fa fa-circle-o"></i>Asignación
                    Horarios</a>
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
                    <small>Asignar Horario</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-12">
                        @if(session('mensaje'))
                            <div class="callout callout-danger">
                                <p>{{session('mensaje')}}</p>
                            </div>
                        @endif
                        <div class="col-sm-12">
                            <div class="callout callout-success">
                                <h4>@foreach($grupo as $gp) {{$gp->grado}} {{$gp->grupo}}@endforeach --
                                    {{$carrera->nombre}} -- @if($carrera->id_modalidad) {{"ESCOLARIZADO"}}
                                    @else {{"ESCOLARIZADO"}} @endif</h4></div>
                            <h3>
                            </h3>
                        </div>

                        <div class="col-sm-12">
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
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>8:00 - 9:00</th>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>9:00 - 10:00</th>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>10:00 - 11:00</th>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>11:00 - 12:00</th>
                                        <td>
                                            <a class="btn btn-app" width="100%">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>12:00 - 13:00</th>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>13:00 - 14:00</th>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>14:00 - 15:00</th>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-app ">
                                                <i class="fa fa-plus-square fa-5x" style="font-size: 2.5em"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection
@section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {

        });
    </script>
@endsection