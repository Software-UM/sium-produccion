<div class="form form-horizontal">
    @if(session('data'))
        <input type="hidden" name="idGrupo" id="idGrupo"
               value=@foreach($response->grupo as $gp) {{$gp->id}}@endforeach>
        <input type="hidden" name="idciclo" id="idciclo" value={{$response->ciclo}}>
        <input type="hidden" name="idCarrera" id="idCarrera" value={{$carrera->id}}>
        <input type="hidden" name="dia" id="dia">
        <input type="hidden" name="hora" id="hora">
    @endif
    <div class="form-group">
        <label class="control-label col-sm-3">Docente:</label>
        <div class="col-sm-8">
            <select class="form-control select2" style="width: 100%;" name="empleado"
                    id="empleado">
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
            <select class="form-control select2" style="width: 100%;" name="materia"
                    id="materia">
                <option disabled selected>SELECCIONA UNA MATERIA</option>
                @if(session('data'))
                    @foreach($response->materia as $mat)
                        <option value="{{$mat->clave}}">{{$mat->nombre}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Numero de horas:</label>
        <div class="col-sm-8">
            <select class="form-control" name="horas" id="horas">
                <option value="0" selected>1</option>
                <option value="1">2</option>
                <option value="2">3</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6">
            <button type="submit" class="btn btn-flat bg-olive pull-right"
                    onclick="doAsignacion()"><i
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