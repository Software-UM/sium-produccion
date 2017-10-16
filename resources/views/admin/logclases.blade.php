@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style type="text/css">

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
            <li class="active"><a href="{{ url("modules/personal/horario/grupo") }}"><i class="fa fa-circle-o"></i>Ver carga
                    Horarios</a>
            </li>
            <li ><a href="{{ url("modules/personal/horario/grupo/seleccionar") }}"><i class="fa fa-circle-o"></i>Asignación
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
            <li class="active">Grupos</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Modificaciones
                    <small>Carga Académica</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                            <input type="text" id="searchbox" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                    <table id="tablaGrupos" class="table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th class="col-md-2 col-xs-2">Nombre Docente</th>
                            <th class="col-md-1 col-xs-1">Hora entrada</th>
                            <th class="col-md-1 col-xs-1">Hora salida</th>
                            <th class="col-md-2 col-xs-2">Carrera</th>
                            <th class="col-md-2 col-xs-1">Materia</th>
                            <th class="col-md-2 col-xs-2">Responsable</th>
                            <th class="col-md-1 col-xs-1">Acción</th>
                            <th class="col-md-1 col-xs-1">Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
						    <?php $i = 1?>
                            @foreach($listas as $lista)
                                <tr id={{$i}}>
                                    <td>{{$lista->nombred}}</td>
                                    <td>{{$lista->hora_entrada}}</td>
                                    <td>{{$lista->hora_salida}}</td>
                                    <!--<td>{{$lista->grado}}° {{$lista->grupo}}</td>-->
                                    <td>{{$lista->c}}</td>
                                    <td>{{$lista->nombre}}</td>
                                    <td>{{$lista->nombree}}</td>
									<?php
                                        $response = "";
                                        switch ($lista->tipo){
                                            case 1:
                                            	$response ="NUEVO";
                                            	break;
                                            case 2:
                                            	$response = "ACTUALIZACIÓN";
                                            	break;
                                            case 3:
                                            	$response = "ELIMINACIÓN";
                                        }
                                    ?>
                                    <td>{{$response}}</td>
                                    <td>{{ date('d-M-Y G:i:s',strtotime($lista->fecha))}}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ asset('components/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('components/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            var dataTable = $('#tablaGrupos').dataTable({
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
            $("#tablaGrupos_filter").addClass("pull-right");
            $("#tablaGrupos_paginate").addClass("pull-right");
        });
    </script>
@endsection