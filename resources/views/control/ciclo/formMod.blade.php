@extends('layout.principal')
@section('title',"Modificar Ciclo")
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
            <li><a href="#">Ciclos</a></li>
            <li class="active">Modificar</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Módulo Ciclos
                    <small>Modificar Ciclo</small>
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
                      action="{{url("/modules/escolar/ciclos/modify")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="idCiclo" id="idCiclo" value={{$ciclo->getId()}}>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombre" name="nombre" class="form-control input-sm"
                            value="{{$ciclo->getNombre()}}"/>
                            @if($errors->has('nombre'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('nombrec') ? ' has-error' : '' }}">
                        <label for="nombrec" class="col-sm-2 control-label">Nombre Corto</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombrec" name="nombrec" class="form-control input-sm"
                            value="{{$ciclo->getNombreCorto()}}"/>
                            @if($errors->has('nombrec'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombrec') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('descripcion') ? 'has-error':''}}">
                        <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
                        <div class="col-sm-5">
                            <textarea style="overflow:auto;resize:none" class="form-control" name="descripcion" id="descripcion">{{$ciclo->getDescripcion()}}</textarea>
                            @if($errors->has('descripcion'))
                                <p class="help-block">
                                    <strong>{{$errors->first('descripcion')}}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Ciclo</button>
                    </div>
                </form>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection