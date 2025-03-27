<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>
<body >
<?php
					  if($nivel=='Doctor' or $nivel=='Enfermera'){
					?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
Emergencias
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
		    <div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Emergencias
			</div>

			<div class="col-md-2 recortar">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>
		</div>
	</div>
	<div class="container">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th style="display:none;">Cod. de emergencia</th> 
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Hora de Ingreso</th>
                    <th>Fecha de Ingreso</th>               
                    <th>Cédula del Paciente</th>
                    <th>Nombre del Personal</th>
                    <th>Apellido del Personal</th>
                    <th>Cédula del Personal</th>
                </tr>
            </thead>
            <tbody id="resultadoconsulta">
                <?php if ($datos['resultado'] == 'consultar'): ?>
                    <?php foreach ($datos['datos'] as $fila): ?>
                        <tr>
                            <td>
                                <div class="button-containerotro" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 10px">
                                    <a type="button" class="btn btn-success" onclick="pone(this,0)"
                                        horaingreso="<?= htmlspecialchars($fila['horaingreso']) ?>"
                                        fechaingreso="<?= htmlspecialchars($fila['fechaingreso']) ?>"
                                        motingreso="<?= htmlspecialchars($fila['motingreso']) ?>"
                                        diagnostico_e="<?= htmlspecialchars($fila['diagnostico_e']) ?>"
                                        tratamientos="<?= htmlspecialchars($fila['tratamientos']) ?>"
                                        cedula_p="<?= htmlspecialchars($fila['cedula_p']) ?>"
                                        cedula_h="<?= htmlspecialchars($fila['cedula_h']) ?>"
                                        cargo="<?= htmlspecialchars($fila['cargo']) ?>"
                                        nombre="<?= htmlspecialchars($fila['nombre']) ?>"
                                        apellido="<?= htmlspecialchars($fila['apellido']) ?>">
                                        <img src="img/lapiz.svg" style="width: 20px">
                                    </a>
									<a type="button" class="btn btn-danger" onclick="pone(this,1)"
                                        horaingreso="<?= htmlspecialchars($fila['horaingreso']) ?>"
                                        fechaingreso="<?= htmlspecialchars($fila['fechaingreso']) ?>"
                                        motingreso="<?= htmlspecialchars($fila['motingreso']) ?>"
                                        diagnostico_e="<?= htmlspecialchars($fila['diagnostico_e']) ?>"
                                        tratamientos="<?= htmlspecialchars($fila['tratamientos']) ?>"
                                        cedula_p="<?= htmlspecialchars($fila['cedula_p']) ?>"
                                        cedula_h="<?= htmlspecialchars($fila['cedula_h']) ?>"
                                        cargo="<?= htmlspecialchars($fila['cargo']) ?>"
                                        nombre="<?= htmlspecialchars($fila['nombre']) ?>"
                                        apellido="<?= htmlspecialchars($fila['apellido']) ?>">
                                        <img src="img/basura.svg" style="width: 20px">
                                    </a>
									<a type="button" class="btn btn-primary" onclick="pone(this,2)"
                                        horaingreso="<?= htmlspecialchars($fila['horaingreso']) ?>"
                                        fechaingreso="<?= htmlspecialchars($fila['fechaingreso']) ?>"
                                        motingreso="<?= htmlspecialchars($fila['motingreso']) ?>"
                                        diagnostico_e="<?= htmlspecialchars($fila['diagnostico_e']) ?>"
                                        tratamientos="<?= htmlspecialchars($fila['tratamientos']) ?>"
                                        cedula_p="<?= htmlspecialchars($fila['cedula_p']) ?>"
                                        cedula_h="<?= htmlspecialchars($fila['cedula_h']) ?>"
                                        cargo="<?= htmlspecialchars($fila['cargo']) ?>"
                                        nombre="<?= htmlspecialchars($fila['nombre']) ?>"
                                        apellido="<?= htmlspecialchars($fila['apellido']) ?>">
                                        <img src="img/ojo.svg" style="width: 20px">
                                    </a>
									<a type="button" class="btn btn-danger" href="vista/fpdf/consultasm.php?cod_consulta=<?= htmlspecialchars($fila['cod_emergencia']) ?>" target="_blank">
									<img src="img/descarga.svg" style="width: 20px;"></a>
									
									
                                    <!-- Agrega los demás botones aquí -->
                                </div>
                            </td>
                            <td style="display:none;"><?= htmlspecialchars($fila['cod_emergencia']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre_h']) ?></td>
                            <td><?= htmlspecialchars($fila['apellido_h']) ?></td>
                            <td><?= htmlspecialchars($fila['horaingreso']) ?></td>
                            <td><?= htmlspecialchars($fila['fechaingreso']) ?></td>
                            <td><?= htmlspecialchars($fila['cedula_h']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['apellido']) ?></td>
                            <td><?= htmlspecialchars($fila['cedula_p']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Formulario de Emergencias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
		<div class="container">
		   <form method="post" id="f" autocomplete="off">
			<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
			<div class="container">	
				<div class="row mb-3">
				
					<div class="col-md-6" style="display: none;">
					   <label class="texto-inicio font-medium" for="cod_emergencia">Cod. de Emergencia</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cod_emergencia" />
					   <span id="scod_emergencia"></span>
					</div>
					
					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="horaingreso">Hora de Ingreso</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="time" id="horaingreso" />
					   <span id="shoraingreso"></span>
					</div>

                    <div class="col-md-6">
					   <label class="texto-inicio font-medium" for="fechaingreso">Fecha de Ingreso</label>
					   <input class="form-control bg-gray-200 rounded-lg border-white" type="date" id="fechaingreso" />
					   <span id="fechaingreso"></span>
					</div>
				</div>

				<br>

                <div class="row mb-3">
				    <div class="col-md-6" >
					   <label class="texto-inicio font-medium" for="motingreso">Motivo de Ingreso</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="motingreso"></textarea>
					   <span id="smotingreso"></span>
					</div>

					<div class="col-md-6">
					   <label class="texto-inicio font-medium" for="diagnostico_e">Diagnostico</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="diagnostico_e"></textarea>
					   <span id="sdiagnostico_e"></span>
					</div>
                    
				</div>
				<div class="row mb-3">

					<div class="col-md-12">
					   <label class="texto-inicio font-medium" for="tratamientos">Tratamientos</label>
					   <textarea rows="2" cols="25" class="form-control bg-gray-200 rounded-lg border-white" type="text" id="tratamientos"></textarea>
					   <span id="stratamientos"></span>
					</div>
				</div>

					<div class="col-md-12">
						<label class="texto-inicio font-medium" for="cedula_p">Cedula del Personal</label>
						<div class="col-md-8 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_p" name="cedula_p" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_personal" name="cedula_personal" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepersonal" name="listadodepersonal">LISTADO DE PERSONAL</button>
						</div>
						<span id="scedula_p"></span>
					</div>
					<div class="row">
						<div class="col-md-12" id="datosdelpersonal">
						
						</div>
					</div>
					<br>

					<label class="texto-inicio font-medium" for="cedula_h">Cedula del Paciente</label>
						<div class="col-md-8 input-group">
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_h" name="cedula_h" />				
							<input class="form-control bg-gray-200 rounded-lg border-white" type="text" id="cedula_historia" name="cedula_historia" style="display:none"/>
							<button type="button" class="btn btn-primary" id="listadodepacientes" name="listadodepacientes">LISTADO DE PACIENTES</button>
						</div>
						<span id="scedula_h"></span>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12" id="datosdelpacientes">
						
						</div>
					</div>
					<br>
					
				                  
				</div>

				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>


				<div class="row mt-3 justify-content-center mb-4">
					<div class="col-md-2">
						   <button style="color: white;" type="button" class="btn botonverde" id="proceso"></button>
					</div>
				</div>
				
			</div>	
			</form>
		</div> 
		
    </div>
	
  </div>
</div>

<!-- seccion del modal personal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalpersonal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Personal</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<table class="table table-striped table-hover" id="tabladelpersonal">
		<thead>
		  <tr>
		    <th style="display:none">Id</th>
			<th>Cedula</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Cargo</th>
		  </tr>
		</thead>
		<tbody id="listadopersonal">
		 
		</tbody>
		</table>
    </div>
  </div>
</div>
<!--fin de seccion modal-->


<!-- seccion del modal historias -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalpacientes">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info gradiente">
        <h5 class="modal-title">Listado de Pacientes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-content">
		<table class="table table-striped table-hover" id="tablahistorias">
		<thead>
		  <tr>
		    <th style="display:none">Id</th>
			<th>Cedula</th>
			<th>Nombre</th>
			<th>Apellido</th>
		  </tr>
		</thead>
		<tbody id="listadopacientes">

		</tbody>
		</table>
    </div>
  </div>
</div>
<!--fin de seccion modal-->



<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/emergencias.js"></script> 
<?php
}					?>
</body>
</html>