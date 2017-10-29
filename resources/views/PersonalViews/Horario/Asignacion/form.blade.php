@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{asset('components/plugins/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css')}}">
@endsection
@section('menuLateral')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i><span>Personal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal') }}"><i class="fa fa-arrow-circle-right"></i> Gestión del Personal</a></li>
            <li><a href="{{ url('modules/personal/tipoempleado') }}"><i class="fa fa-arrow-circle-right"></i> Tipos de Personal</a>
            </li>
            <li><a href="{{ url('modules/personal/puesto') }}"><i class="fa fa-arrow-circle-right"></i> Puestos</a></li>
        </ul>
    </li>
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-clock-o"></i><span> Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{url('modules/personal/horario')}}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li class="active"><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i>
                    Asignación Horario Personal</a>
            </li>
            <li><a href="{{ url('modules/personal/horario/tipo') }}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="{{ url('modules/personal/reportes') }}">
            <i class="fa fa-area-chart"></i></i> <span>Reportes</span></i>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Personal
            <small>Asignacion Horario</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li><a href="#">Asignación</a></li>
            <li class="active">Agregar</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Nueva Asignación Horario</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    @if(session('mensaje'))
                        <div class="callout callout-danger">
                            <p>{{session('mensaje')}}</p>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="callout callout-success">
                            <p>{{session('success')}}</p>
                        </div>
                    @endif
                    <form role="form" method="POST" class="form-horizontal"
                          action="{{ url('/modules/personal/horario/asignacion/add') }}">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('departamento') ? ' has-error' : '' }}">
                            <label for="departamento" class="col-sm-2 control-label">Departamento</label>
                            <div class="col-sm-5">
                                <select id="departamento" name="departamento" class="form-control input-sm select2"
                                        data-placeholder="Selecciona los departamentos" onchange="getEmpleados()">
                                    <option disabled selected>SELECCIONA UN DEPARTAMENTO</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value={{$departamento->id}}>{{$departamento->nombre}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('departamento'))
                                    <p class="help-block">
                                        <strong>{{ $errors->first('departamento') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-sm-2 control-label">Tipo Horario</label>
                            <div class="col-sm-5">
                                <select name="tipo[]" id="tipo[]" class="form-control select2"
                                        data-placeholder="Selecciona los Horarios" multiple>
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
                        <div class="form-group" id="contenido">

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
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <!-- BootStrap-Switch-->
    <script src="{{asset('components/plugins/bootstrap-switch-master/dist/js/bootstrap-switch.js')}}"></script>
    <!-- bootstrap time picker-->
    <script src="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function getEmpleados() {
            var idDep = $("#departamento").val();
            $("#contenido").empty();
            $.ajax({
                type: "POST",
                url: '{{ url("modules/personal/horario/asignacion/add/getempleado")}}',
                data: {
                    id: idDep,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                },
                success: function (data, textStatus, jqXHR) {
                    var option = "";
                    data.forEach(function (entry) {
                        option += "<div class='col-sm-12'><label class='control-label col-sm-4'>" + entry['nombre'].toUpperCase()
                                + "</label> <input type='checkbox' name='empleados[]' " +
                                "data-size='small' data-on-color='success' value=" + entry['id'] + " checked></div>";
                    })
                    $("#contenido").append(option);
                    $("[name='empleados[]']").bootstrapSwitch();
                }
            });
        }
        $(function () {
            $(".select2").select2();
            $('.clockpicker').clockpicker();
        });

    </script>
@endsection