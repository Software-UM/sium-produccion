@extends('layout.principal')
@section('title',"Nuevo Grupo")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection
@section('menuLateral')
    <li class="treeview">
        <a href="/modules/escolar/materia">
            <i class="fa fa-book"></i> <span>Materias</span>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/carrera">
            <i class="fa fa-graduation-cap"></i> <span>Carreras</span>
        </a>
    </li>
    <li class="treeview active">
        <a href="/modules/escolar/grupos">
            <i class="fa fa-list-ul"></i> <span>Grupos</span>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/ciclos">
            <i class="fa fa-calendar"></i> <span>Ciclos</span>
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
            <li><a href="#">Grupos</a></li>
            <li class="active">Agregar</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Módulo Grupos
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
                      action="{{url("/modules/escolar/grupos/add")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="grado" class="col-sm-2 control-label">Grado</label>
                        <div class="col-sm-5">
                            <select id="grado" name="grado" class="form-control">
                                <option value="1">1°</option>
                                <option value="2">2°</option>
                                <option value="3">3°</option>
                                <option value="4">4°</option>
                                <option value="5">5°</option>
                                <option value="6">6°</option>
                                <option value="7">7°</option>
                                <option value="8">8°</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('grupo') ? ' has-error' : '' }}">
                        <label for="nombrec" class="col-sm-2 control-label">Grupo</label>
                        <div class="col-sm-5">
                            <input type="text" id="grupo" name="grupo" class="form-control input-sm"/>
                            @if($errors->has('grupo'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('grupo') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('modalidad') ? 'has-error':''}}">
                        <label for="modalidad" class="col-sm-2 control-label">Modalidad</label>
                        <div class="col-sm-5">
                            <select id="modalidad" name="modalidad" class="form-control input-sm">
                                @foreach($modalidad as $mod)
                                    <option value="{{$mod->id}}">{{$mod->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Grupo</button>
                    </div>
                </form>

            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection