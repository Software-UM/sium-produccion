@extends('layout.principal')
@section('title',"Menu principal")
@section('css')
    <!-- Bootstrap time Picker-->
    <link rel="stylesheet"
          href="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css')}}">
    <style type="text/css">

    </style>
@endsection
@section('menuLateral')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
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
            <li><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li ><a href="{{ url('modules/personal/horario/tipo') }}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
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
            <small>Horario</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li class="active">Horario</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Nuevo Horario</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    <form role="form" method="POST" class="form-horizontal"
                          action="{{ url('/modules/personal/horario/modify') }}">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('entrada') ? ' has-error' : '' }}">
                            <label for="entrada" class="col-sm-2 control-label">Hora Entrada</label>

                            <div class="col-sm-5 ">
                                <div class="input-group clockpicker" data-align="top"
                                     data-autoclose="true">
                                    <input type="text" class="form-control" name="entrada" id="entrada">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                @if ($errors->has('entrada'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('entrada') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('salida') ? ' has-error' : '' }}">
                            <label for="salida" class="col-sm-2 control-label">Hora Salida</label>
                            <div class="col-sm-5 ">
                                <div class="input-group clockpicker" data-align="top"
                                     data-autoclose="true">
                                    <input type="text" class="form-control timepicker2" name="salida" id="salida">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                @if ($errors->has('salida'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('salida') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('turno') ? ' has-error' : '' }}">
                            <label for="turno" class="col-sm-2 control-label">Turno</label>
                            <div class="col-sm-5">
                                <select id="turno" name="turno" class="form-control">
                                    <option></option>
                                    <option value=1>Matutino</option>
                                    <option value=2>Vespertino</option>
                                </select>
                                @if ($errors->has('turno'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('turno') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('checkboxvar') ? ' has-error' : '' }}">
                            <label for="checkboxvar[]" class="col-sm-2 control-label">Día</label>
                            <div class="col-sm-5">
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='0'
                                                                      id='checkboxvar[]'>L</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='1'
                                                                      id='checkboxvar[]'>M</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='2'
                                                                      id='checkboxvar[]'>Mi</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='3'
                                                                      id='checkboxvar[]'>J</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='4'
                                                                      id='checkboxvar[]'>V</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='5'
                                                                      id='checkboxvar[]'>S</label>
                                <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='6'
                                                                      id='checkboxvar[]'>D</label>
                                @if ($errors->has('checkboxvar'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('checkboxvar') }}</strong>
                                    </p>
                                @endif
                            </div>

                        </div>
                        <div class="form-group {{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-sm-2 control-label">Tipo Horario</label>
                            <div class="col-sm-5">
                                <select name="tipo" id="tipo" class="form-control">
                                    <option></option>
                                    @foreach($tipos as $tipo)
                                        <option value={{$tipo->id}}>{{$tipo->descripcion}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('tipo'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
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

@section('js')

    <!-- bootstrap time picker-->
    <script src="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script>
        $(function () {
            $('.clockpicker').clockpicker();
        });

    </script>
@endsection