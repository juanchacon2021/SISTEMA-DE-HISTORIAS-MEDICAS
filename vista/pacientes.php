<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");
require_once("comunes/notificaciones.php");	
?>

<body>
<?php  
    if (!isset($permisos)) {
       
        header("Location: ?pagina=login");
        exit();
    } elseif (!in_array('Pacientes', $permisos)) {
        
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    } ?>

<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
Pacientes
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
			<div style="color: white;" class="col-md-2 botonverde" style="cursor: pointer;" onclick='pone(this,3), limpiarm()' >
				Registrar Paciente
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
					<th>Cédula</th>
					<th>Apellido</th>
					<th>Nombre</th>
					<th>Fecha de Nacimiento</th>
					<th>Edad</th>
					<th>Teléfono</th>
					<th style="display: none;">Estado Civil</th>
					<th style="display: none;">Dirección</th>
					<th style="display: none;">Ocupación</th>
					<th style="display: none;">HDA</th>
					<th style="display: none;">Hábito Tóxico</th>
					<th style="display: none;">Alergias</th>
					<th style="display: none;">Alergias Medicas</th>
					<th style="display: none;">Quirurgico</th>
					<th style="display: none;">Psicosocial</th>
					<th style="display: none;">Transsanguineo</th>
					<th style="display: none;">Antecedentes Padre</th>
					<th style="display: none;">Antecedentes Madre</th>
					<th style="display: none;">Antecedentes Hermano</th>
				</tr>
			</thead>
			<tbody id="resultadoconsulta">
                
            </tbody>
		</table>
	</div>
</div>
</div>

<!-- SECCION MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="width: 60rem;">
      <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="container">
        <form method="post" id="f" autocomplete="off" style="margin: 0em 2em 2em 2em;">
          <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">

          <!-- Primera Parte -->
          <div class="container step" id="step-1">
            <div class="row mb-6">
				<h1 class="text-2xl font-bold mb-2">Información General</h1>

				<div>
					<div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
					</div>
				</div>

              <div class="col-md-6">
                <label for="cedula_historia" class="texto-inicio font-medium mb-2">Cedula *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Cedula" type="text" id="cedula_historia" name="cedula_historia" />
                <span id="scedula_historia"></span>
              </div>
              <div class="col-md-6">
                <label for="nombre" class="texto-inicio font-medium mb-2">Nombres *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Nombres" type="text" id="nombre" name="nombre" />
                <span id="snombre"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="apellido" class="texto-inicio font-medium mb-2">Apellidos *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Apellidos" type="text" id="apellido" name="apellido" />
                <span id="sapellido"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="fecha_nac" class="texto-inicio font-medium mb-2">Fecha de Nacimiento *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Fecha de Nacimiento" type="date" id="fecha_nac" name="fecha_nac" />
                <span id="sfecha_nac"></span>
              </div>
			  <div class="col-md-6 mt-4">
                <label for="edad" class="texto-inicio font-medium mb-2">Edad *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Edad" type="number" id="edad" name="edad" />
                <span id="sedad"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="estadocivil" class="texto-inicio font-medium mb-2">Estado Civil *</label>
                <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Estado Civil" id="estadocivil" name="estadocivil">
                  <option value="" selected>-- Seleccione --</option>
                  <option value="SOLTERO">Soltero</option>
                  <option value="CASADO">Casado</option>
                  <option value="DIVORCIADO">Divorciado</option>
                  <option value="VIUDO">Viudo</option>
                </select>
              </div>
			  <div class="col-md-6 mt-4">
                <label for="telefono" class="texto-inicio font-medium mb-2">Telefono *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Telefono" type="text" id="telefono" name="telefono" />
                <span id="stelefono"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="direccion" class="texto-inicio font-medium mb-2">Direccion *</label>
                <input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Direccion" type="text" id="direccion" name="direccion" />
                <span id="sdireccion"></span>
              </div>
            </div>
          </div>

          <!-- Segunda Parte -->
          <div class="container step d-none" id="step-2">
            <div class="row mb-3">

				<h1 class="text-2xl font-bold mb-2">Historia Médica</h1>

				<div>
					<div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
					</div>
				</div>

				<div class="col-md-6 mt-4">
					<label for="ocupacion" class="texto-inicio font-medium mb-2">Ocupacion *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Ocupacion" type="text" id="ocupacion" name="ocupacion" />
					<span id="socupacion"></span>
              	</div>

			    <div class="col-md-6 mb-2 mt-4">
					<label for="alergias" class="texto-inicio font-medium mb-2">Alergias *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Alergias" type="text" id="alergias"
						name="alergias"/>
					<span id="salergias"></span>
				</div>
				<div class="col-md-6 mt-4">
					<label for="alergias_med" class="texto-inicio font-medium mb-2">Alergias Médicas *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Alergias Medicas" type="text" id="alergias_med"
							name="alergias_med"/>
					<span id="salergias_med"></span>
				</div>
				<div class="col-md-6 mt-4">
					<label for="habtoxico" class="texto-inicio font-medium mb-2">Habito Toxico *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Habito Toxico" type="text" id="habtoxico"
							name="habtoxico"/>
					<span id="shabtoxico"></span>
				</div>
			</div>
			<div class="row mb-6">
				<div class="col-md-4 mt-4">
					<label for="quirurgico" class="texto-inicio font-medium mb-2">Quirurgico *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Quirurgico" type="text" id="quirurgico"
							name="quirurgico"/>
					<span id="squirurgico"></span>
				</div>
				
				<div class="col-md-4 mt-4">
					<label for="transsanguineo" class="texto-inicio font-medium mb-2">Trans Sanguineos *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Trans Sanguineos" type="text" id="transsanguineo"
							name="transsanguineo"/>
					<span id="stranssanguineo"></span>
				</div>

				<div class="col-md-4 mt-4">
					<label for="hda" class="texto-inicio font-medium mb-2">HDA *</label>
					<input class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="HDA" type="text" id="hda"
							name="hda"/>
					<span id="shda"></span>
				</div>
			  </div>
            </div>
        

          <!-- Tercera Parte -->
          <div class="container step d-none" id="step-3">
            <div class="row mb-3">
				
			<h1 class="text-2xl font-bold mb-2">Historia Médica</h1>

			<div>
				<div class="col-md-1 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
				</div>
			</div>

			  <div class="col-md-6 mt-4">
			  	<label for="psicosocial" class="texto-inicio font-medium mb-2">Psicosocial *</label>
                <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Psicosocial" name="psicosocial" id="psicosocial"></textarea>
                <span id="spsicosocial"></span>
			  </div>
              <div class="col-md-6 mt-4">
                <label for="antc_madre" class="texto-inicio font-medium mb-2">Antecedentes Maternos *</label>
                <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Antecedentes Maternos" name="antc_madre" id="antc_madre"></textarea>
                <span id="santc_madre"></span>
              </div>
              <div class="col-md-6 mt-4">
                <label for="antc_padre" class="texto-inicio font-medium mb-2">Antecedentes Paternos *</label>
                <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Antecedentes Paternos" name="antc_padre" id="antc_padre"></textarea>
                <span id="santc_padre"></span>
              </div>
			  <div class="col-md-6 mt-4">
                <label for="antc_hermano" class="texto-inicio font-medium mb-2">Antecedentes de Hermanos *</label>
                <textarea class="form-control bg-gray-200 rounded-lg border-white p-3" placeholder="Antecedentes de Hermanos" name="antc_hermano" id="antc_hermano"></textarea>
                <span id="santc_hermano"></span>
              </div>
            </div>
          </div>

          <!-- Botones de Navegación -->
          <div class="row mt-5 mb-5 justify-content-between">
            <div class="col-md-3 abajo">
              <button type="button" class="" id="prev-btn" style="display: none;">&lt; Anterior</button>
            </div>
            <div class="col-md-3 text-end abajo"">
              <button type="button" id="next-btn">Siguiente &gt;</button>
            </div>
          </div>
        </form>
		</div>
      </div>
    </div>
  </div>
</div>

<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/pacientes.js"></script>

</body>
</html>