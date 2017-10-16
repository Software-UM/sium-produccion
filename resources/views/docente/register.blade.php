@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
@endsection
@section('contenido')
    <section class="content-header">
        <h1>
            Crear Usuario Docente
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li class="active">Personal</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Gestión Personal</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    <form class="form-horizontal" role="form" method="post" action="{{ url('/docente/register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('empleado') ? ' has-error' : '' }}">
                            <label for="empleado" class="col-md-4 control-label">Seleccione un empleado</label>
                            <div class="col-md-6">
                                <select class="form-control select2" id="empleado" name="empleado">
                                    @foreach($empleados as $empleado)
                                        <option value={{$empleado->id}}>{{$empleado->nombre}} {{$empleado->apellidos}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                            <label for="usuario" class="col-md-4 control-label">Usuario</label>

                            <div class="col-md-6">
                                <input id="usuario" type="text" class="form-control" name="usuario" value="{{ old('usuario') }}">

                                @if ($errors->has('usuario'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('usuario') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Seleccione tipo empleado</label>
                            <div class="col-md-6">
                                <select class="form-control" id="role" name="role">
                                        <option value=2 selected>Docente</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Register
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

@section("js")
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection