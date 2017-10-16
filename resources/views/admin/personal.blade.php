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
        <a href="/admin/">
            <i class="fa fa-area-chart"></i></i> <span>Admin panel</span></i>
        </a>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li class="active"><a href="/modules/personal/"><i class="fa fa-arrow-circle-right"></i> Gestión del
                    Personal</a></li>
            <li><a href="/modules/personal/tipoempleado"><i class="fa fa-arrow-circle-right"></i> Tipos de Personal</a>
            </li>
            <li><a href="/modules/personal/puesto"><i class="fa fa-arrow-circle-right"></i> Puestos</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="/modules/personal/horario"><i class="fa fa-circle-o"></i> Horarios</a></li>
            <li><a href="/modules/personal/horario/asignacion"><i class="fa fa-circle-o"></i> Asignación Personal</a>
            </li>
            <li><a href="/modules/personal/horario/tipo"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="/modules/personal/reportes">
            <i class="fa fa-area-chart"></i></i> <span>Reportes</span></i>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Personal

        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li class="active">Personal</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión Personal</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    @if(session('mensaje'))
                        <div class="callout callout-success">
                            <p>{{session('mensaje')}}</p>
                        </div>
                    @elseif(session('error'))
                        <div class="callout callout-warning">
                            <p>{{session('error')}}</p>
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
                            <a class="btn btn-block btn-social btn-bitbucket" href="{{url('/modules/personal/add')}}">
                                <i class="fa fa-plus"></i> Agregar Empleado
                            </a>
                        </div>
                    </div>
                    <table id="tablaGrupos" class="table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th class="col-md-3 col-xs-3">Nombre</th>
                            <th class="col-md-2 col-xs-2">Departamento</th>
                            <th class="col-md-3 col-xs-3">Puesto</th>
                            <th class="col-md-4 col-xs-4">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1?>
                        @foreach($empleados as $empleado)
                            <tr id={{$empleado->id}}>
                                <th scope='row'>{{$i++}}</th>
                                <td>{{$empleado->nombre}}</td>
                                <td>{{$empleado->depa}}</td>
                                <td>{{$empleado->descripcion}}</td>
                                <td>
                                    <a class="btn btn-danger" title="Eliminar Empleado"
                                       onclick="setValue('{{ $empleado->id }}','{{ $empleado->nombre }}',this)"><i
                                                class="fa fa-trash fa-lg"></i></a>
                                    <a href="{{url("/modules/personal/")}}/modify/{{$empleado->id}}"
                                       class="btn btn-primary" title="Modificar tipo empleado">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </a>
                                    <a href="{{ url("/modules/personal/horario/empleado/") }}/{{$empleado->id}}"
                                       title="Horario" class="btn btn-warning">
                                        <i class="fa fa-clock-o fa-lg"></i>
                                    </a>
                                    <a class="btn btn-success" title="Ver empleado"
                                       onclick="verEmpleado('{{$empleado}}')">
                                        <i class="fa fa-eye fa-lg"></i></a>
                                    @if($empleado->descripcion == 'DOCENTE')
                                        <a href="{{url("/modules/personal/")}}/qr/{{$empleado->id}}"
                                           class="btn bg-purple" title="Generar QR">
                                            <i class="fa fa-qrcode fa-lg"></i></a>
                                    @endif
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
                    <h4 class="modal-title">Baja Empleado</h4>
                </div>
                <div class="modal-body">
                    <form class="form">

                    </form>
                    <p>¿Deseas Eliminar el empleado?</p>
                    <blockquote id="nombreempleado"></blockquote>
                    <input type="text" name="id" id="id" hidden>
                    <input type="text" name="descript" id="descript" hidden>
                    <input type="number" name="row" id="row" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="deleteRow()">Aceptar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal view -->
    <div class="modal fade" id="verEmpleado" role="dialog">
        <div class="modal-dialog">
            <!--Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Información general</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <blockquote id="nomEmp"></blockquote>
                            </div>
                            <div class="col-sm-6">
                                <img src="{{asset('components/dist/img/avatar5.png')}}" id="imgEmpleado" width="150">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label>Fecha Ingreso</label>
                            <blockquote id="fechaEmp"></blockquote>
                            <label>Profesión</label>
                            <blockquote id="profesionEmp"></blockquote>
                            <label>Departamento</label>
                            <blockquote id="departamentoEmp"></blockquote>
                            <label>Puesto Laboral</label>
                            <blockquote id="cargoEmp"></blockquote>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Baja Empleado</h4>
                </div>
                <form role="form" method="POST" class="form-horizontal"
                      action="{{url("/modules/personal/delete")}}">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="idP" id="idP"/>
                        <!-- NOMBRE -->
                        <div class="form-group">
                            <label for="descripcion" class="col-sm-3 control-label">Descripcion</label>
                            <div class="col-sm-8">
                                <textarea id="descripcion" name="descripcion" class="form-control input-sm"
                                          placeholder="Detalle de la baja ..." required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tipo" class="col-sm-3 control-label">Detalle de la baja</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="tipoBaja" id="tipoBaja">
                                    @foreach($tipos as $tipo)
                                        <option value="{{$tipo->id}}">{{$tipo->motivo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Aceptar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
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
        function verEmpleado(empleado) {
            var jsonEmpleado = JSON.parse(empleado);
            $("#nomEmp").empty();
            $("#nomEmp").append("<h4>" + jsonEmpleado.nombre + "</h4>");
            var monthNames = [
                "Enero", "Febrero", "Marzo",
                "Abril", "Mayo", "Junio", "Julio",
                "Augosto", "Septiembre", "Octubre",
                "Noviembre", "Diciembre"
            ];
            $('#imgEmpleado').attr('src',"{{url('components/dist/img/avatar5.png')}}");
            var date = new Date(jsonEmpleado.created_at);
            var day = date.getDate();
            var monthIndex = date.getMonth();
            var year = date.getFullYear();
            $("#fechaEmp").empty();
            $("#fechaEmp").append("<h5>" + day + " - " + monthNames[monthIndex] + " - " + year + "</h5>");
            $("#profesionEmp").empty();
            $("#profesionEmp").append("<h5>" + jsonEmpleado.profesion + "</h5>");
            $("#departamentoEmp").empty();
            $("#departamentoEmp").append("<h5>" + jsonEmpleado.depa + "</h5>");
            $("#cargoEmp").empty();
            $("#cargoEmp").append("<h5>" + jsonEmpleado.descripcion + "</h5>");
            //imagen empleado
            $.ajax({
                url:"{{url("")}}/uploads/"+jsonEmpleado.id+'.jpg',
                type:'HEAD',
                error: function()
                {
                    //file not exists
                },
                success: function()
                {
                    //file exists
                    $('#imgEmpleado').attr('src','../uploads/'+jsonEmpleado.id+'.jpg');
                }
            });
            $("#verEmpleado").modal();
        }
        function setValue(id, descripcion, row) {
            $("#myModal").modal();
            $("#id").val(id);
            $("#descript").val(descripcion);
            $("#nombreempleado").empty();
            $("#nombreempleado").append("<h2>" + descripcion + "</h2>");
            var i = row.parentNode.parentNode.rowIndex;
            $("#row").val(i);
        }
        function deleteRow() {
            var idg = $("#id").val();
            var i = $("#row").val();
            $("#myModal").modal("hide");
            $("#idP").val(idg);
            $("#myModal2").modal();
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
