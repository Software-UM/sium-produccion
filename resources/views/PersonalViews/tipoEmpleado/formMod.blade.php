@extends('layout.principal')
@section('title',"Menu principal")
<meta name="csrf-token" content="{{ csrf_token() }}"/>
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
        <a href=" {{ url("admin/") }}">
            <i class="fa fa-area-chart"></i></i> <span>Admin panel</span></i>
        </a>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url("modules/personal/") }}"><i class="fa fa-arrow-circle-right"></i> Gestión del Personal</a></li>
            <li><a href="{{ url("modules/personal/tipoempleado")}}"><i class="fa fa-arrow-circle-right"></i> Tipos de Personal</a></li>
            <li><a href="{{ url("modules/personal/puesto") }}"><i class="fa fa-arrow-circle-right"></i> Puestos</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href=" {{ url("modules/personal/horario") }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li><a href="{{ url("modules/personal/horario/asignacion/add")}}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li><a href=" {{ url("modules/personal/horario/tipo") }}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="{{ url("modules/personal/reportes")}}">
            <i class="fa fa-area-chart"></i></i> <span>Reportes</span></i>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Personal
            <small>Planteles</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Personal</a></li>
            <li><a href="#">Plantel</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión Campus
                    <small>Agregar nuevo</small>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(session('mensaje'))
                    <div class="callout callout-danger">
                        <p>{{session('mensaje')}}</p>
                    </div>
                @endif
                <form role="form" method="POST" class="form-horizontal"
                      action="{{url("/modules/personal/tipoempleado/modify")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="idTipo" value="{{$tipo->getId()}}" />
                    <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Descripción</label>
                        <div class="col-sm-5">
                            <input type="text" id="descripcion" name="descripcion" class="form-control input-sm" value="{{$tipo->getDescripcion()}}"/>
                            @if ($errors->has('descripcion'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Tipo</button>
                    </div>
                </form>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection
