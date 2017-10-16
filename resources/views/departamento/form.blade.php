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
            <li><a href="#">Personal</a></li>
            <li><a href="#">Departamentos</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión de Departamentos
                    <small>Agregar Departamentos</small>
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
                      action="{{url("/modules/personal/departamento/add")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombre" name="nombre" class="form-control input-sm"/>
                            @if ($errors->has('nombre'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                        <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
                        <div class="col-sm-5">
                            <textarea style="overflow:auto;resize:none" class="form-control" name="descripcion" id="descripcion"></textarea>
                            @if ($errors->has('descripcion'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Puesto</button>
                    </div>
                </form>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection