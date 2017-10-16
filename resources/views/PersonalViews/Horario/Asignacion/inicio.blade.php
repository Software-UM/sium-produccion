@extends('layout.principal')
@section('title',"Menu principal")
@section('css')
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
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal') }}"><i class="fa fa-arrow-circle-right"></i> Gestión del Personal</a></li>
            <li><a href="{{ url('modules/personal/tipoempleado') }}"><i class="fa fa-arrow-circle-right"></i> Tipos de Personal</a>
            </li>
            <li><a href="{{ url('modules/personal/puesto') }}"><i class="fa fa-arrow-circle-right"></i> Puestos</a></li>
        </ul>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-clock-o"></i><span> Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal/horario') }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li class="active"><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li ><a href="{{url('modules/personal/horario/tipo')}}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="{{ url('modules/personal/reportes') }}">
            <i class="fa fa-area-chart"></i> <span>Reportes</span></i>
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
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li class="active">Horario</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión Horarios</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    @if(session('mensaje'))
                        <div class="callout callout-success">
                            <p>{{session('mensaje')}}</p>
                        </div>
                    @endif
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                            <input type="text" id="searchbox" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                    <div class="col-md-offset-2 col-sm-3 pull-right">
                        <div class="input-group">
                            <a class="btn btn-block btn-social btn-bitbucket"
                               href="{{url('/modules/personal/horario/asignacion/add')}}">
                                <i class="fa fa-plus"></i> Agregar Horario
                            </a>
                        </div>
                    </div>
                    <table id="tablaGrupos" class="table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th class="col-md-2 col-xs-2">Descripcion</th>
                            <th class="col-md-3 col-xs-3">Hora Entrada</th>
                            <th class="col-md-3 col-xs-2">Hora Salida</th>
                            <th class="col-md-3 col-xs-3">Acciones</th>
                        </tr>
                        </thead>
                        <?php $i = 1;?>
                        <tbody>

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
        function setValue(idG, idC, idCarrera, modalidad, row) {
            $("#myModal").modal();
            $("#idgp").val(idG);
            $("#idciclo").val(idC);
            $("#idCarrera ").val(idCarrera);
            $("#mod").val(modalidad);
            var i = row.parentNode.parentNode.rowIndex;
            $("#row").val(i);
        }
        function deleteRow() {

            var idg = $("#idgp").val();
            var idciclo = $("#idciclo").val();
            var idcarrera = $("#idCarrera ").val();
            var mod = $("#mod").val();
            var i = $("#row").val();
            document.getElementById("tablaGrupos").deleteRow(i);
            /**/
            var rpost = $.post("actas/delete", {
                idg: idg,
                idciclo: idciclo,
                idcarrera: idcarrera,
                mod: mod,
            });
            rpost.success(function (result) {
                alert("Grupo eliminado");

            });
            rpost.error(function (result, status, ss) {
                alert("Error" + result.responseText);
            });
            rpost.complete(function () {
                //alert("ajax complete");
            });
            $("#myModal").modal("hide");
        }
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