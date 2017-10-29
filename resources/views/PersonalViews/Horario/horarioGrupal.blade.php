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
                <h3 class="box-title">Gestión de Horarios
                    <small>Asignar grupo</small>
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
                    @if($bloqueo->estado == 1)
                            <div class="callout callout-warning">
                                <p><strong>Las modificaciones de cargas académicas estan suspendidas</strong></p>
                            </div>
                    @endif
                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                            <input type="text" id="searchbox" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                        @if (Auth::user()->tipo == 1 || Auth::user()->id == 25 || Auth::user()->id == 28)
                            @if($bloqueo->estado == 1)
                                <div class="col-md-offset-1 col-sm-3 ">
                                    <div class="input-group pull-right">
                                        <a class="btn btn-block btn-social btn-bitbucket" onclick="quitBloqueo({{$bloqueo->id}})">
                                            <i class="fa fa-plus"></i> Activar Cargas
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-3 pull-right">
                                    <div class="input-group">
                                        <a class="btn btn-block btn-social btn-openid" onclick="setBloqueo({{$bloqueo->id}})">
                                            <i class="fa fa-plus"></i> Bloquear Cargas Academicas
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif

                    <table id="tablaGrupos" class=" display table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th class="col-md-1 col-xs-1">Grupo</th>
                            <th class="col-md-3 col-xs-3">Carrera</th>
                            <th class="col-md-1 col-xs-1">Modalidad</th>
                            <th class="col-md-2 col-xs-2">Ciclo</th>
                            <th class="col-md-1 col-xs-1">Salón</th>
                            <th class="col-md-1 col-xs-1">Edificio</th>
                            <th class="col-md-3 col-xs-3">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1?>
                        @foreach($grupos as $grupo)
                            <tr id={{$grupo->id}}>
                                <th scope='row'>{{$i++}}</th>
                                <td>{{$grupo->grado}}° {{$grupo->grupo}}</td>
                                <td>{{$grupo->nombre}}</td>
                                <td>@if($grupo->id_modalidad == 1) ESCOLARIZADO @else SEMIESCOLARIZADO @endif</td>
                                <td>{{$grupo->nombre_corto}}</td>
                                <td>{{$grupo->numero}}</td>
                                <td>{{$grupo->edificio}}</td>
                                <td>
                                    @if($bloqueo->estado == 0)
                                        <a href="{{url("/modules/personal/horario/grupo")}}/modify/{{$grupo->id}}/carrera/{{$grupo->id_carrera}}/ciclo/{{$grupo->id_ciclo}}/salon/{{$grupo->id_salon}}"
                                           class="btn btn-primary" title="Modificar Horario">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                    @endif

                                    <a class="btn btn-danger" title="Ver Horario" target="_blank"
                                       href="{{url("/modules/personal/horario/grupo")}}/reporte/{{$grupo->id}}/carrera/{{$grupo->id_carrera}}/ciclo/{{$grupo->id_ciclo}}/mod/{{$grupo->id_modalidad}}">
                                        <i class="fa fa-file-pdf-o fa-lg"></i>
                                    </a>
                                   
                                    <a class="btn btn-info" title="Modificar Salon" target="_blank" onclick="showSalones('{{$grupo->id_salon}}','{{$grupo->id}}','{{$grupo->id_ciclo}}','{{$grupo->id_carrera}}')">
                                        <i class="fa fa-users fa-lg"></i>
                                    </a>
                                    
                                        <a href="{{url("/modules/personal/horario/grupo")}}/qr/{{$grupo->id}}/carrera/{{$grupo->id_carrera}}/ciclo/{{$grupo->id_ciclo}}/salon/{{$grupo->id_salon}}"
                                           class="btn btn-warning"  title="Generar QR">
                                            <i class="fa fa-qrcode fa-lg"></i>
                                        </a>
                              
                                </td>
                            </tr>
                        @endforeach

                        @foreach($gruposSemi as $gruposs)
                            <tr id={{$gruposs->id}}>
                                <th scope='row'>{{$i++}}</th>
                                <td>{{$gruposs->grado}}° {{$gruposs->grupo}}</td>
                                <td>{{$gruposs->nombre}}</td>
                                <td>@if($gruposs->dia == 5) SABADOS @else DOMINGOS @endif</td>
                                <td>{{$gruposs->nombre_corto}}</td>
                                <td>{{$gruposs->numero}}</td>
                                <td>{{$gruposs->edificio}}</td>
                                <td>
                                    <a href="{{url("/modules/personal/horario/grupo")}}/modify/{{$gruposs->id}}/carrera/{{$gruposs->id_carrera}}/ciclo/{{$gruposs->id_ciclo}}/salon/{{$gruposs->id_salon}}"
                                       class="btn btn-primary" title="Modificar Horario">
                                        <i class="fa fa-pencil fa-lg"></i>
                                    </a>
                                    <a class="btn btn-danger" title="Ver Horario" target="_blank"
                                       href="{{url("/modules/personal/horario/grupo")}}/reporte/{{$gruposs->id}}/carrera/{{$gruposs->id_carrera}}/ciclo/{{$gruposs->id_ciclo}}/mod/{{$gruposs->id_modalidad}}">
                                        <i class="fa fa-file-pdf-o fa-lg"></i>
                                    </a>
                                    <a class="btn btn-info" title="Modificar Salon" target="_blank" onclick="showSalones('{{$gruposs->id_salon}}','{{$gruposs->id}}','{{$gruposs->id_ciclo}}','{{$gruposs->id_carrera}}')">
                                        <i class="fa fa-users fa-lg"></i>
                                    </a>
                                    @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 8)
                                        <a href="{{url("/modules/personal/horario/grupo")}}/qr/{{$gruposs->id}}/carrera/{{$gruposs->id_carrera}}/ciclo/{{$gruposs->id_ciclo}}/salon/{{$gruposs->id_salon}}/dia/{{$gruposs->dia}}"
                                           class="btn btn-warning"  title="Generar QR">
                                            <i class="fa fa-qrcode fa-lg"></i>
                                        </a>
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
    @include('PersonalViews.Horario.modalEdificio')
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

        function showSalones(idSalon,idGrupo,idCiclo,idCarrera){
            $("#idGrupoHorario").val(idGrupo);
            $("#idCicloHorario").val(idCiclo);
            $("#idSalonOld").val(idSalon);
            $("#idCarreraA").val(idCarrera);
            $("#modalEdifio").modal();
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
                        $("#salones").append("<option value="+value["id"]+">"+value["numero"]+"</option>");
                    });
                }
            });
        }
        
        function setBloqueo(id) {
            $.ajax({
                type: "POST",
                url: "{{url('modules/personal/horario/bloqueo')}}",
                data: {
                    id: id,
                    estado:1
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
                    if(data["success"]){
                        swal(
                            'Correcto!',
                            'Se activo el bloqueo',
                            'success'
                        );
                        location.reload();
                    }else{
                        swal(
                            'UPS!',
                            data["error"],
                            'warning'
                        );
                    }
                }
            });
        }
        function quitBloqueo(id) {
            $.ajax({
                type: "POST",
                url: "{{url('modules/personal/horario/bloqueo')}}",
                data: {
                    id: id,
                    estado:0
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
                    if(data["success"]){
                        swal(
                            'Correcto!',
                            'Se desactivo el bloqueo',
                            'success'
                        );
                        location.reload();
                    }else{
                        swal(
                            'UPS!',
                            data["error"],
                            'warning'
                        );
                    }
                }
            });
        }
        function setEdificios() {
            var idSalon = $("#salones").val();
            if (idSalon == null){
                swal(
                    'Atención!',
                    'Favor de llenar todos los campos',
                    'warning'
                );
            }else{
                var ciclo = $("#idCicloHorario").val();
                var idGrupo = $("#idGrupoHorario").val();
                var idSalonOld = $("#idSalonOld").val();
                var idCarrera = $("#idCarreraA").val();
                var modalidad = $("#modds").val();
                $.ajax({
                    type: "GET",
                    url: "{{url('modules/personal/horario/grupo/salones/validar')}}",
                    data: {
                        idSalon: idSalon,
                        ciclo: ciclo,
                        idGrupo: idGrupo,
                        modalidad: modalidad
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
                        if(data["success"]){
                            updateSalon(idSalonOld, ciclo,idGrupo,idSalon,idCarrera,modalidad)
                        }else{
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
        function updateSalon(idSalon, ciclo,idGrupo,idSalonNuevo,idCarrera,dia){
            $.ajax({
                type: "POST",
                url: "{{url('modules/personal/horario/grupo/salones')}}",
                data: {
                    idSalon: idSalon,
                    ciclo: ciclo,
                    idGrupo: idGrupo,
                    idSalonNuevo: idSalonNuevo,
                    idCarrera: idCarrera,
                    dia: dia
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
                    if(data["success"]){
                        swal(
                            'Correcto!',
                            data["success"],
                            'success'
                        );
                        location.reload(true);
                    }else{
                        swal(
                            'Atención!',
                            'Salón no disponible',
                            'warning'
                        );
                    }
                }
            });
        }
        function closeModal(){
            $("#modalEdifio").modal("hide");
        }

        $(function () {

            var dataTable = $('#tablaGrupos').dataTable({
                initComplete: function() {
                    this.api().columns([1,2,3,4,5,6]).every(function () {                        
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function(){
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                        column.data().unique().sort().each(function(d, j){
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                },
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
                    "infoFiltered": "(filtrados de un total de _MAX_ entradas)",
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