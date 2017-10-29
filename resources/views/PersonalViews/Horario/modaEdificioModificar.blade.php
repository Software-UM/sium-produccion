<div class="modal fade" id="modalEdificiom" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal Content -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeModal2()">&times;</button>
                <h4 class="modal-title">Asignar salón</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-3">Edificio:</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" style="width: 100%;" name="edificiom"
                                            id="edificiom" onchange="buscarSalon2()">
                                        <option disabled selected>SELECCIONA UN EDIFICIO</option>
                                        @if(isset($response->edificios))
                                            @foreach($response->edificios as $edificio)
                                                <option value="{{$edificio->id}}"> {{$edificio->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach($response as $edificio)
                                                <option value="{{$edificio->id}}"> {{$edificio->nombre}}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Salón:</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" style="width: 100%;" name="salonesm"
                                            id="salonesm">
                                        <option disabled selected>SELECCIONA UN SALÓN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-flat bg-olive pull-right"
                                            onclick="modEdificios()"><i
                                                class="fa fa-floppy-o"></i> Guardar
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-danger pull-left" onclick="closeModal2()">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>