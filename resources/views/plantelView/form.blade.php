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
    <li class="treeview active">
        <a href="/modules/personal/plantel">
            <i class="fa fa-university"></i> <span>Campus</span>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/personal/departamento">
            <i class="fa fa-home"></i></i> <span>Departamentos</span></i>
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
                <form role="form" method="POST" class="form-horizontal"
                      action="{{url("/modules/personal/plantel/add")}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label for="nombre" class="col-sm-2 control-label">Nombre del plantel</label>
                        <div class="col-sm-5">
                            <input type="text" id="nombre" name="nombre" class="form-control input-sm"/>
                            @if ($errors->has('nombre'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('clave') ? ' has-error' : '' }}">
                        <label for="clave" class="col-sm-2 control-label">Clave</label>
                        <div class="col-sm-5">
                            <input type="text" id="clave" name="clave" class="form-control input-sm">
                            @if ($errors->has('clave'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('clave') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="turno" class="col-sm-2 control-label">Estado</label>
                        <div class="col-sm-5">
                            <select id="estados" name="estados" class="form-control input-sm" onchange="getMunicipios()">
                                @foreach ($estados as $estado)
                                    <option value={{$estado->id}}>{{$estado->estado}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('municipio') ? ' has-error' : '' }}">
                        <label for="municipio" class="col-sm-2 control-label">Municipio</label>
                        <div class="col-sm-5">
                            <select id="municipio" name="municipio" class="form-control input-sm">
                            </select>
                            @if ($errors->has('municipio'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('municipio') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('calle') ? ' has-error' : '' }}">
                        <label for="calle" class="col-sm-2 control-label">Calle</label>
                        <div class="col-sm-5">
                            <input type="text" id="calle" name="calle" class="form-control input-sm">
                            @if ($errors->has('calle'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('calle') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('colonia') ? ' has-error' : '' }}">
                        <label for="colonia" class="col-sm-2 control-label">Colonia</label>
                        <div class="col-sm-5">
                            <input type="text" id="colonia" name="colonia" class="form-control input-sm">
                            @if ($errors->has('colonia'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('colonia') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('cp') ? ' has-error' : '' }}">
                        <label for="cp" class="col-sm-2 control-label">Código postal</label>
                        <div class="col-sm-2">
                            <input type="text" id="cp" name="cp" class="form-control input-sm col-xs-3">
                            @if ($errors->has('cp'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('cp') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <button type="submit" class="btn btn-primary pull-right">Guardar Plantel</button>
                    </div>
                </form>

            </div>

            <!-- /.box-body -->
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ asset('components/dist/js/estados.js')}}"></script>
    <script type="text/javascript">

        function getMunicipios() {
            $("#municipio").empty();
            var idEstados = $("#estados").val();
            var data = {
                id : idEstados,
            };

            $.post('add/municipios',data, function(data,status){
                var optionEstados = "";
                data.forEach(function(entry) {
                    optionEstados+= "<option value="+entry['id']+" >"+entry['nombre_municipio']+"</option>";
                });
                $("#municipio").append(optionEstados);
            });
        }
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
    </script>
@endsection