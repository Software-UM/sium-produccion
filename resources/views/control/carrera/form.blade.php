@extends('layout.principal')
@section('title','Nueva Carrera')
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
    <li class="treeview">
        <a href="/modules/escolar/grupos">
            <i class="fa fa-list-ul"></i> <span>Grupos</span>
        </a>
    </li>
    <li class="treeview active">
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
            <li><a href="#">carrera</a></li>
            <li class="active">Agregar</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Módulo Carreras
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
                      action="{{url("/modules/escolar/carrera/add")}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombre" name="nombre" class="form-control input-sm"/>
                            @if($errors->has('nombre'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('rvoe') ? ' has-error' : '' }}">
                        <label for="rvoe" class="col-sm-2 control-label">RVOE</label>
                        <div class="col-sm-5">
                            <input type="text" id="rvoe" name="rvoe" class="form-control input-sm"/>
                            @if($errors->has('rvoe'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('rvoe') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('plantel') ? 'has-error':''}}">
                        <label for="plantel" class="col-sm-2 control-label">Plantel</label>
                        <div class="col-sm-5">
                            <select name="plantel" id="plantel" class="form-control">
                                @foreach($planteles as $plantel)
                                    <option value="{{$plantel->cct}}">{{$plantel->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('modalidad') ? 'has-error':''}}">
                        <label for="modalidad" class="col-sm-2 control-label">Modalidad</label>
                        <div class="col-sm-5">
                            <select name="modalidad" id="modalidad" class="form-control">
                                @foreach($modalidad as $mod)
                                    <option value="{{$mod->id}}">{{$mod->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Carrera</button>
                    </div>
                </form>

            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection