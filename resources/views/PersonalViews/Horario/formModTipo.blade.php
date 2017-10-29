@extends('layout.principal')
@section('title',"Menu principal")
@section('menuLateral')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i><span>Personal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal') }}"><i class="fa fa-arrow-circle-right"></i> Gestión del Personal</a></li>
        </ul>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-clock-o"></i><span> Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal/horario') }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li ><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li class="active"><a href="{{ url('modules/personal/horario/tipo') }}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
    <li class="active">
        <a href="{{url('modules/personal/reportes')}}">
            <i class="fa fa-area-chart"></i><span>Reportes</span>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Personal
            <small>Tipo Horario</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li><a href="#">Horario</a></li>
            <li class="active">Tipo</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Modificar Tipo Horario</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(session('mensaje'))
                    <div class="callout callout-danger">
                        <p>{{session('mensaje')}}</p>
                    </div>
                @endif
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    <form role="form" method="POST" class="form-horizontal"
                          action="{{ url('/modules/personal/horario/tipo/modify') }}">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{$tipos->id}}" name="id" id="id"/>
                        <input type="hidden" value="{{$tipos->idParam}}" name="idParam" id="idParam"/>
                        <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                            <label class="control-label col-sm-2" for="descripcion">Descripción</label>
                            <div class="col-sm-5">
                                <input type="text" id="descripcion" name="descripcion" class="form-control"
                                value="{{$tipos->descripcion}}"/>
                                @if ($errors->has('descripcion'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="col-sm-12">
                            <h4>Configuración de tiempos</h4>
                        </div>
                        <div class="form-group col-sm-12 {{ $errors->has('antes') ? ' has-error' : '' }}">
                            <label class="control-label col-sm-2" for="antes">Tiempo antes</label>
                            <div class="col-sm-5">
                                <input type="number" id="antes" name="antes" class="form-control"
                                       value="{{$tipos->tiempo_antes}}"/>
                                @if ($errors->has('antes'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('antes') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-sm-12 {{ $errors->has('despues') ? ' has-error' : '' }}">
                            <label class="control-label col-sm-2" for="despues">Tiempo despues</label>
                            <div class="col-sm-5">
                                <input type="number" id="despues" name="despues" class="form-control"
                                       value="{{$tipos->tiempo_despues}}"/>
                                @if ($errors->has('despues'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('despues') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-7">
                                <button type="submit" class="btn btn-social btn-bitbucket pull-right"><i
                                            class="fa fa-floppy-o"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection
