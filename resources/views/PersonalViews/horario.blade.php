@extends('layout.principal')
@section('title',"Menu principal")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <!-- Bootstrap time Picker-->
    <link rel="stylesheet"
          href="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.css')}}">
@endsection
@section('menuLateral')
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li class="active"><a href="{{ url('modules/personal') }}"><i class="fa fa-arrow-circle-right"></i> Gestión del
                    Personal</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-clock-o"></i><span> Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('modules/personal/horario') }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li><a href="{{ url('modules/personal/horario/asignacion/add') }}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li><a href="{{ url('modules/personal/horario/tipo') }}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
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
                <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="{{ asset('components/dist/img/avatar5.png')}}"
                         alt="user image">
                    <span class="username">
                          <a href="#">{{strtoupper($empleados->getNombre())}} {{ strtoupper($empleados->getApellidos()) }}</a>
                    </span>
                    <span class="description">{{ $empleados->getProfesion() }}</span>
                </div>
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
                    <div class="col-sm-12">
                        <center><h2 class="page-header">
                                <i class="fa fa-clock-o"></i> Horarios Asignados.
                            </h2></center>
                    </div>
                    <div class="col-md-offset-2 col-sm-3 pull-right">
                        <div class="input-group">
                            <a class="btn btn-block btn-social btn-bitbucket" onclick="horario()">
                                <i class="fa fa-plus"></i> Agregar Horario
                            </a>
                        </div>
                    </div>
                    <table id="tablaGrupos" class="table table-bordered table-hover table-striped"
                           style="font-size: 12px;">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th class="col-md-3 col-xs-2">Nombre</th>
                            <th class="col-md-2 col-xs-2">Hora Entrada</th>
                            <th class="col-md-2 col-xs-2">Hora Salida</th>
                            <th class="col-md-2 col-xs-2">Dia</th>
                            <th class="col-md-3 col-xs-3">Acciones</th>
                        </tr>
                        </thead>
                        <?php $i = 1;
                        $arrayDias = ['lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                        ?>
                        <tbody>
                        @if(count($horarios)>0)
                            @foreach($horarios as $horario)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$horario->descripcion}}</td>
                                    <td>{{$horario->hora_entrada}}</td>
                                    <td>{{$horario->hora_salida}}</td>
                                    <td>{{ strtoupper($arrayDias[$horario->dia]) }}</td>
                                    <td>
                                        <a class="btn btn-danger" title="Eliminar Empleado"
                                           onclick="setValue('{{ $horario->idA }}',this)"><i
                                                    class="fa fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <th colspan="6">NO HAY RESULTADOS</th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Horario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div role="form" class="form-horizontal">
                            <input type="hidden" name="idEmpleado" id="idEmpleado" value="{{$empleados->getId()}}">
                            <div class="form-group {{ $errors->has('entrada') ? ' has-error' : '' }}">
                                <label for="entrada" class="col-sm-3 control-label">Hora Entrada</label>
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
                                <label for="salida" class="col-sm-3 control-label">Hora Salida</label>
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
                            <div class="form-group {{ $errors->has('checkboxvar') ? ' has-error' : '' }}">
                                <label for="checkboxvar[]" class="col-sm-3 control-label">Día</label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='0'
                                                                          class="checkboxvar">L</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='1'
                                                                          class="checkboxvar">M</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='2'
                                                                          class="checkboxvar">Mi</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='3'
                                                                          class="checkboxvar">J</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='4'
                                                                          class="checkboxvar">V</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='5'
                                                                          class="checkboxvar">S</label>
                                    <label class="checkbox-inline"><input type='checkbox' name='checkboxvar[]' value='6'
                                                                          class="checkboxvar">D</label>
                                    @if ($errors->has('checkboxvar'))
                                        <p class="help-block">
                                            <strong>{{ $errors->first('checkboxvar') }}</strong>
                                        </p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="guardar()">Aceptar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal eliminar -->
    <div class="modal fade" id="eliminar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Eliminar Horario</h4>
                </div>
                <div class="modal-body">
                    <blockquote><b>¿Deseas Eliminar el horario?</b></blockquote>
                    <input type="text" name="idelim" id="idelim" hidden>
                    <input type="number" name="row" id="row" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="deleteRow()">Eliminar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{asset('components/plugins/select2/select2.full.min.js')}}"></script>
    <!-- bootstrap time picker-->
    <script src="{{ asset('components/plugins/clockpicker-gh-pages/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function horario() {
            $("#myModal").modal();
        }

        function setValue(id,row) {
            $("#eliminar").modal();
            $("#idelim").val(id);
            var i = row.parentNode.parentNode.rowIndex;
            $("#row").val(i);
        }
        function deleteRow() {

            var idg = $("#idelim").val();
            var i = $("#row").val();
            $.ajax({
                type: "POST",
                url: "{{ url("modules/personal/horario/elim/single")}}",
                data: {
                    id: idg,
                },
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    swal(
                            'Error!',
                            errorThrown,
                            'error'
                    );
                },
                success: function (data, textStatus, jqXHR) {
                    swal(
                            'Correcto!',
                            "Se elimino la asignación de horario",
                            'success'
                    );
                    document.getElementById("tablaGrupos").deleteRow(i);
                    $("#eliminar").modal("hide");
                }
            });
        }

        function guardar() {
            var entrada = $("#entrada").val();
            var salida = $("#salida").val();
            var idEmpleado = $("#idEmpleado").val();
            if (entrada == '' || salida == '' || $('input[name^=checkboxvar]:checked').length <= 0) {
                swal(
                        'Atención!',
                        "Favor de llenar todos los campos",
                        'warning'
                );
            } else {
                var array = [];
                var i = 0;
                $(".checkboxvar").each(function (index) {
                    if ($(this).is(':checked')) {
                        array[i] = $(this).val();
                        i++;
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{{url("modules/personal/horario/add/single")}}",
                    data: {
                        entrada: entrada,
                        salida: salida,
                        turno: '1',
                        checkboxvar: array,
                        tipo: 8,
                        idEmpleado:idEmpleado,
                    },
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    },
                    success: function (data, textStatus, jqXHR) {
                        alert(data.error);
                        location.reload();
                    }
                });
            }
        }
        $(function () {
            $(".select2").select2();
            $('.clockpicker').clockpicker();
        });

    </script>
@endsection