@extends('layout.principal')
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
    <li class="treeview">
        <a href="/modules/personal/plantel">
            <i class="fa fa-university"></i> <span>Campus</span>
        </a>
    </li>
    <li class="treeview active">
        <a href="/modules/personal/departamento">
            <i class="fa fa-home"></i></i> <span>Departamentos</span></i>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Personal
            <small>Departamentos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li class="active">Departamentos</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión Departamentos</h3>
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
                               href="{{url('/modules/personal/departamento/add')}}">
                                <i class="fa fa-plus"></i> Agregar Departamento
                            </a>
                        </div>
                    </div>
                    <table id="tablaGrupos" class="table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th class="col-md-4 col-xs-4">Nombre</th>
                            <th class="col-md-5 col-xs-5">Descripcion</th>
                            <th class="col-md-3 col-xs-3">Acciones</th>
                        </tr>
                        </thead>
                        <?php
                        $i = 1;
                        ?>
                        <tbody>
                        @foreach($departamentos as $departamento)
                            <tr id={{ $departamento->id}}>
                                <th scope='row'>{{ $i++ }}</th>
                                <td>{{ $departamento->nombre }}</td>
                                <td>{{ $departamento->descripcion }}</td>
                                <td>
                                    <a class="btn btn-danger" title="Eliminar tipo empleado"
                                       onclick="setValue('{{ $departamento->id }}','{{$departamento->descripcion}}',this)"><i
                                                class="fa fa-trash fa-lg"></i></a>
                                    <a href="{{url("/modules/personal/departamento/")}}/modify/{{$departamento->id}}"
                                       class="btn btn-primary" title="Modificar Departamento">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Eliminar Registro Departamento</h4>
                </div>
                <div class="modal-body">
                    <p>¿Deseas Eliminar el Departamento?</p>
                    <input type="text" name="id" id="id" hidden>
                    <input type="text" name="descript" id="descript" hidden>
                    <input type="number" name="row" id="row" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="deleteRow()">Aceptar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
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
        function setValue(id, descripcion, row) {
            $("#myModal").modal();
            $("#id").val(id);
            $("#descript").val(descripcion);
            var i = row.parentNode.parentNode.rowIndex;
            $("#row").val(i);
        }
        function deleteRow() {

            var idg = $("#id").val();
            var idciclo = $("#descript").val();
            var i = $("#row").val();
            document.getElementById("tablaGrupos").deleteRow(i);
            var rpost = $.post("departamento/delete", {
                id: idg,
                descripcion: idciclo,
            });
            rpost.success(function (result) {
                alert(result);

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