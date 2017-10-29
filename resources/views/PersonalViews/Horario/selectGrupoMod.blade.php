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

        .btnp {
            padding: 1px 3px !important;
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
            <li class="active"><a href="{{ url("modules/personal/horario/grupo") }}"><i class="fa fa-circle-o"></i>Ver carga
                    Horarios</a>
            </li>
            <li ><a href="{{ url("modules/personal/horario/grupo/seleccionar")}}"><i class="fa fa-circle-o"></i>Asignación
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
                    <small>Modificar grupo</small>
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

                    <div class="col-sm-12">&nbsp;</div>
                    <hr>
                    <div class="col-md-12">
                        <div class="col-sm-12">
                            <p class="text-center">
                                <strong>{{$carrera->getNombre()}} {{$grupo->getGrado()}} {{$grupo->getGrupo()}} @if($grupo->getIdModalidad() == 1) {{"ESCOLARIZADO"}}
                                    @else {{"SEMIESCOLARIZADO"}} @endif </strong>
                            </p>
                        </div>
                        <?php
                        //hacemos la descarga de los datos
                        //print($arrayHoras[0]);

                        ?>
                        <div class="col-sm-12">
                            <input type="hidden" id="idSalon" value="{{$idSalon}}">
                        @if($grupo->getIdModalidad() == 1)
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
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[0][$i]))
                                                            @if($arrayHoras[0][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(0,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>
                                                <div id="b01">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[0][$i]))
                                                            @if($arrayHoras[0][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(0,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b02">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[0][$i]))
                                                            @if($arrayHoras[0][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,2)">
                                                                        <i class="fa fa-pencil"></i></a></p>

                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(0,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b03">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[0][$i]))
                                                            @if($arrayHoras[0][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[0][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[0][$i]->idClase}}','{{$arrayHoras[0][$i]->idHorario}}','{{$arrayHoras[0][$i]->id}}',0,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(0,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>

                                        </tr>
                                        <tr>
                                            <th>8:00 - 9:00</th>
                                            <td>
                                                <div id="b10">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b11">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>
                                                <div id="b12">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b13">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>9:00 - 10:00</th>
                                            <td>
                                                <div id="b20">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b21">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b22">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b23">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>10:00 - 11:00</th>
                                            <td>
                                                <div id="b30">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b31">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b32">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b33">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>11:00 - 12:00</th>
                                            <td>
                                                <div id="b40">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b41">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b42">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b43">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>12:00 - 13:00</th>
                                            <td>
                                                <div id="b50">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b51">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b52">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b53">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>13:00 - 14:00</th>
                                            <td>
                                                <div id="b60">
													<?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
																<?php $find = true; ?>
																<?php $i = 4; ?>
                                                            @endif
                                                        @else
															<?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b61">
													<?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
																<?php $find = true; ?>
																<?php $i = 4; ?>
                                                            @endif
                                                        @else
															<?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b62">
													<?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
																<?php $find = true; ?>
																<?php $i = 4; ?>
                                                            @endif
                                                        @else
															<?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b63">
													<?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
																<?php $find = true; ?>
																<?php $i = 4; ?>
                                                            @endif
                                                        @else
															<?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                         <tr>
                                            <th>14:00 - 15:00</th>
                                            <td>
                                                <div id="b70">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 0)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,0)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,0)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,0)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b71">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 1)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,1)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,1)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,1)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b72">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 2)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,2)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,2)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,2)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b73">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<4;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 3)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,3)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,3)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 4; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,3)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
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
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b16">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[1][$i]))
                                                            @if($arrayHoras[1][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[1][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[1][$i]->idClase}}','{{$arrayHoras[1][$i]->idHorario}}','{{$arrayHoras[1][$i]->id}}',1,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(1,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>9:00 - 10:00</th>
                                            <td>
                                                <div id="b25">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b26">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[2][$i]))
                                                            @if($arrayHoras[2][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[2][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[2][$i]->idClase}}','{{$arrayHoras[2][$i]->idHorario}}','{{$arrayHoras[2][$i]->id}}',2,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(2,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>10:00 - 11:00</th>
                                            <td>
                                                <div id="b35">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b36">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[3][$i]))
                                                            @if($arrayHoras[3][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[3][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[3][$i]->idClase}}','{{$arrayHoras[3][$i]->idHorario}}','{{$arrayHoras[3][$i]->id}}',3,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(3,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>11:00 - 12:00</th>
                                            <td>
                                                <div id="b45">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b46">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[4][$i]))
                                                            @if($arrayHoras[4][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[4][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[4][$i]->idClase}}','{{$arrayHoras[4][$i]->idHorario}}','{{$arrayHoras[4][$i]->id}}',4,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(4,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th>12:00 - 13:00</th>
                                            <td>
                                                <div id="b55">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b56">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[5][$i]))
                                                            @if($arrayHoras[5][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[5][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[5][$i]->idClase}}','{{$arrayHoras[5][$i]->idHorario}}','{{$arrayHoras[5][$i]->id}}',5,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(5,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>13:00 - 14:00</th>
                                            <td>
                                                <div id="b65">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b66">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[6][$i]))
                                                            @if($arrayHoras[6][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[6][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[6][$i]->idClase}}','{{$arrayHoras[6][$i]->idHorario}}','{{$arrayHoras[6][$i]->id}}',6,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(6,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>14:00 - 15:00</th>
                                            <td>
                                                <div id="b75">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 5)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,5)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,5)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,5)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div id="b76">
                                                    <?php $find = false;?>
                                                    @for($i=0;$i<8;$i++)
                                                        @if(isset($arrayHoras[7][$i]))
                                                            @if($arrayHoras[7][$i]->dia == 6)
                                                                <p style="text-align: center">
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nombre}}</span><br>
                                                                    <span class="label label-primary">{{$arrayHoras[7][$i]->nomMateria}}</span>
                                                                </p>
                                                                <p style="text-align: center">
                                                                    <a class="btn btn-danger btnp" title="Eliminar Horario"
                                                                       onclick="eliminar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,6)">
                                                                        <i class="fa fa-trash"></i></a>
                                                                    <a class="btn btn-primary btnp" title="Modificar Horario"
                                                                       onclick="modificar('{{$arrayHoras[7][$i]->idClase}}','{{$arrayHoras[7][$i]->idHorario}}','{{$arrayHoras[7][$i]->id}}',7,6)">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                </p>
                                                                <?php $find = true; ?>
                                                                <?php $i = 7; ?>
                                                            @endif
                                                        @else
                                                            <?php $find = false;  ?>
                                                        @endif
                                                    @endfor
                                                    @if(!$find)
                                                        <a class="btn btn-app btn-flat" onclick="asignar(7,6)">
                                                            <i class="fa fa-plus-square-o fa-5x"
                                                               style="font-size: 2.5em"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            @endif


                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- Modal view add-->
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
                                <input type="hidden" name="idGrupo" id="idGrupo"
                                       value={{$grupo->getId()}}>
                                <input type="hidden" name="idciclo" id="idciclo" value={{$ciclo}}>
                                <input type="hidden" name="idCarrera" id="idCarrera" value={{$carrera->getId()}}>
                                <input type="hidden" name="dia" id="dia">
                                <input type="hidden" name="hora" id="hora">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="empleado">Docente:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" name="empleado"
                                                id="empleado">
                                            <option disabled selected>SELECCIONA UN DOCENTE</option>
                                            @foreach($empleados as $empleado)
                                                <option value="{{$empleado->id}}">{{$empleado->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="materia">Materia:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" name="materia"
                                                id="materia">
                                            <option disabled selected>SELECCIONA UNA MATERIA</option>

                                            @foreach($materia as $mat)
                                                <option value="{{$mat->clave}}">{{$mat->nombre}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="horas">Numero de horas:</label>
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
    <!-- Modal view modificar -->
    <div class="modal fade" id="Modificar" role="dialog">
        <div class="modal-dialog">
            <!-- Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reasignar Horario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form form-horizontal">
                                <input type="hidden" name="idHorario" id="idHorario">
                                <input type="hidden" name="idAsignacion" id="idAsignacion">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="empleadoMod">Docente:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="width: 100%;" name="empleadoMod"
                                                id="empleadoMod">
                                            <option disabled selected>SELECCIONA UN DOCENTE</option>
                                            @foreach($empleados as $empleado)
                                                <option value="{{$empleado->id}}">{{$empleado->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-flat bg-olive pull-right"
                                                onclick="doModificacion()"><i
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
            var idSalon = $("#idSalon").val();
            //var idGrupo =
            if (idEmpleado == null || idMateria == null) {
                swal(
                    'Atención!',
                    'Favor de llenar los campos',
                    'warning'
                );
            } else {
                var nombreEmpleado = $("#empleado option:selected").text();
                var nombreMateria = $("#materia option:selected").text();
                //validar(idEmpleado, idMateria, horas, idGrupo, dia, hora, ciclo, idCarrera);

                var dfd = new jQuery.Deferred();
                var url = "{{ url('modules/personal/horario/grupo/validar') }}";
                $.when(validar(idEmpleado, idMateria, horas, idGrupo, dia,
                        hora, ciclo, idCarrera,idSalon,url)).done(function (data, textStatus, jqXHR) {

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
                        location.reload();
                    } else {
                        swal(
                            'Atención!',
                            data.error,
                            'error'
                        );
                    }
                    //console.log();
                });

            }

        }
        function getCarreras(combo) {
            var mod = $(combo).val();
            var rpost = $.post("{{ url("modules/personal/horario/grupo/carreras/carreras") }}", {
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
            var rpost = $.post("{{url("modules/personal/horario/grupo/carreras/grupos")}}", {
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

        function eliminar(idClase, idAsignacionHora, idHorario, fila, columna) {
            if (confirm("¿Quitar Asignación?")) {
                var url = "{{ url('modules/personal/horario/grupo/eliminar/horario') }}";
                $.when(deleteA(idClase, idAsignacionHora, idHorario,url)).done(function (data, textStatus, jqXHR) {
                    if (data.success) {
                        swal(
                            'Correcto!',
                            data.success,
                            'success'
                        );
                        var inp = "<a class=\"btn btn-app btn-flat\" onclick=\"asignar(" + fila + "," + columna + ")\">" +
                                "<i class=\"fa fa-plus-square-o fa-5x\"" +
                                "style=\"font-size: 2.5em\"></i>" +
                                "</a>";
                        $("#b" + fila + "" + columna).empty();
                        $("#b" + fila + "" + columna).append(inp);
                    } else {
                        swal(
                            'Ups!',
                            data.error,
                            'error'
                        );
                    }
                });
            }

        }

        function modificar(idClase, idAsignacionHora, idHorario, fila, columna) {
            //abrimos el modal y asignamos las variables para el formulario
            $("#idHorario").val(idHorario);
            $("#idAsignacion").val(idAsignacionHora);
            $("#Modificar").modal();

        }

        function doModificacion() {
            var idEmpleado = $("#empleadoMod").val();
            if (idEmpleado == null) {
                swal(
                    'Atención!',
                    'Favor de llenar los campos',
                    'warning'
                );
            } else {
                var idHAsignado = $("#idHorario").val();
                var idAsignacionHora = $("#idAsignacion").val();
                var idSalon = $("#idSalon").val();
                var url = "{{ url('modules/personal/horario/grupo/modificar/horario') }}";
                $.when(modificarA(idHAsignado, idAsignacionHora, idEmpleado,idSalon,url)).done(function (data, textStatus, jqXHR) {
                    if (data.success) {
                        swal(
                            'Correcto!',
                            data.success,
                            'success'
                        );
                        location.reload();
                    } else {
                        swal(
                            'Ups!',
                            data.error,
                            'error'
                        );
                    }
                });
            }
        }
        $(function () {
            $(".select2").select2();
        });
    </script>
@endsection