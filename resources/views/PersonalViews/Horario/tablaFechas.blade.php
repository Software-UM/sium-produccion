<!-- Modal view -->
<div class="modal fade" id="asignarFechaMod" role="dialog">
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
						<p class="text-muted" id="nNombrepMod"></p>
						<h5 class="text-light-blue">Materia:</h5>
						<p class="text-muted" id="nMateriapMod"></p>
                        <!--- AGREGAMOS TODAS LAS VARIABLES A UTILIZAR -->
                        <input type="hidden" id="cicloMod">
                        <input type="hidden" id="idGrupoMod">
                        <input type="hidden" id="idCarreraMod">
                        <input type="hidden" id="idEmpleadoMod">
                        <input type="hidden" id="idMateriaMod">
                        <input type="hidden" id="horaEntradaMod">
                        <input type="hidden" id="horaSalidaMod">
					</div>
					<div class="col-sm-12">
						<div class="form form-horizontal">
							<!-- FECHA NACIMIENTO -->
							<div class="form-group">
								<label for="fechaClaseMod" class="col-sm-3 control-label">Fecha Clase</label>
								<div class="col-sm-8 input-group date"
									 style="padding-left: 15px;padding-right: 15px;">

									<input type="text" id="fechaClaseMod" name="fechaClaseMod"
										   class="form-control input-sm" value="{{date("Y-m-d")}}"/>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6">
									<button type="submit" class="btn btn-flat bg-olive pull-right"
											onclick="doAsignarFechaMod()"><i
											class="fa fa-floppy-o"></i> Guardar
									</button>
								</div>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default pull-left btn-flat bg-red">
										<i class="fa fa-times"></i>
										Terminar
									</button>
								</div>
							</div>
						</div>

					</div>
					<div class="col-sm-12">
						<table id="tablaFechasMod" class="table table-bordered table-hover table-striped"
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
