<div class="col-lg-6">
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Asignacion de horario</h3>
        </div>
        <div class="box-body">
            <div class="form form-horizontal">
                @if(session('data'))
                    <input type="hidden" name="idGrupop" id="idGrupop"
                           value=@foreach($response->grupo as $gp) {{$gp->id}}@endforeach>
                    <input type="hidden" name="idciclop" id="idciclop" value={{$response->ciclo}}>
                    <input type="hidden" name="idCarrerap" id="idCarrerap" value={{$carrera->id}}>
                @endif
                <div class="form-group">
                    <label class="control-label col-sm-3">Docente:</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" style="width: 100%;" name="empleadop"
                                id="empleadop">
                            <option disabled selected>SELECCIONA UN DOCENTE</option>
                            @if(session('data'))
                                @foreach($response->empleados as $empleado)
                                    <option value="{{$empleado->id}}">{{$empleado->nombre}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Materia:</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" style="width: 100%;" name="materiap"
                                id="materiap">
                            <option disabled selected>SELECCIONA UNA MATERIA</option>
                            @if(session('data'))
                                @foreach($response->materia as $mat)
                                    <option value="{{$mat->clave}}">{{$mat->nombre}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('entrada') ? ' has-error' : '' }}">
                    <label for="entrada" class="col-sm-3 control-label">Hora Entrada</label>
                    <div class="col-sm-8">
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
                    <div class="col-sm-8 ">
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
                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-flat bg-olive pull-right"
                                onclick="doAsignacionP()"><i
                                    class="fa fa-floppy-o"></i> Guardar
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="col-lg-6">
    <table id="tablaAsig" class="table table-bordered table-hover table-striped"
           style="font-size: 12px;">
        <thead>
        <tr>
            <th class="col-md-5 col-xs-3">Docente</th>
            <th class="col-md-5 col-xs-2">Materia</th>
            <th class="col-md-2 col-xs-2">Acciones</th>
        </tr>
        </thead>
        <tbody>
        </tbody>

    </table>
</div>


<!-- Modal view -->
<div class="modal fade" id="asignarFecha" role="dialog">
    <div class="modal-dialog">
        <!-- Modal Content -->
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Asignar Fechas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="text-light-blue">Nombre:</h5>
                        <p class="text-muted" id="nNombrep"></p>
                        <h5 class="text-light-blue">Materia:</h5>
                        <p class="text-muted" id="nMateriap"></p>
                    </div>
                    <div class="col-sm-12">
                        <div class="form form-horizontal">
                            <!-- FECHA NACIMIENTO -->
                            <div class="form-group">
                                <label for="fechaClase" class="col-sm-3 control-label">Fecha Clase</label>
                                <div class="col-sm-8 input-group date"
                                     style="padding-left: 15px;padding-right: 15px;">

                                    <input type="text" id="fechaClase" name="fechaClase"
                                           class="form-control input-sm" value="{{date("Y-m-d")}}"/>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-flat bg-olive pull-right"
                                            onclick="doAsignarFecha()"><i
                                                class="fa fa-floppy-o"></i> Guardar
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-default pull-left btn-flat bg-red"
                                            onclick="sendTablep()">
                                        <i class="fa fa-times"></i>
                                        Terminar
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12">
                        <table id="tablaFechas" class="table table-bordered table-hover table-striped"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th class="col-md-3 col-xs-3">Fecha</th>
                                <th class="col-md-4 col-xs-4">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
