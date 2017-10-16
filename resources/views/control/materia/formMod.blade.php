@extends('layout.principal')
@section('title',"Agregar Materia")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection
@section('menuLateral')
    <li class="treeview active">
        <a href="/modules/escolar/materia">
            <i class="fa fa-book"></i></i> <span>Materias</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/carrera">
            <i class="fa fa-graduation-cap"></i></i> <span>Carreras</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/grupos">
            <i class="fa fa-list-ul"></i></i> <span>Grupos</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/ciclos">
            <i class="fa fa-calendar"></i></i> <span>Ciclos</span></i>
        </a>
    </li>
@endsection
@section('contenido')
    <section class="content-header">
        <h1>
            Control
            <small>Escolar</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Control</a></li>
            <li><a href="#">Materia</a></li>
            <li class="active">Modificar</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Módulo Materias
                    <small>Modificar</small>
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
                      action="{{url("/modules/escolar/materia/modify")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" id="claveold" name="claveold" class="form-control input-sm"
                           value="{{$materia->getClave()}}"/>
                    <div class="form-group {{ $errors->has('clave') ? 'has-error':''}}">
                        <label for="clave" class="col-sm-2 control-label">Clave</label>
                        <div class="col-sm-5">
                            <input type="text" id="clave" name="clave" class="form-control input-sm"
                            value="{{$materia->getClave()}}"/>
                            @if($errors->has('clave'))
                                <p class="help-block">
                                    <strong>{{$errors->first('clave')}}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombre" name="nombre" class="form-control input-sm"
                                   value="{{$materia->getNombre()}}"/>
                            @if($errors->has('nombre'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Modificar Materia</button>
                    </div>
                </form>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection