<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
	require_once("comunes/modal.php");
?>
<body >

<div class="container texto-inicio h2 text-center py-8 text-zinc-800">
Registrar Personal
</div>
<div class="container pl-64"> <!-- todo el contenido ira dentro de esta etiqueta-->
   <form method="post" id="f" autocomplete="off">
	
			<div class="container">	
				<div class="row mb-3">
					<div class="col-md-4">
					   <label for="cedula_personal">Cedula</label>
					   <input class="form-control" type="text" id="cedula_personal" name="cedula_personal" />
					   <span id="scedula_personal"></span>
					</div>
					<div class="col-md-8">
					   <label for="apellido">Apellidos</label>
					   <input class="form-control" type="text" id="apellido" name="apellido"/>
					   <span id="sapellido"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-8">
					   <label for="nombre">Nombres</label>
					   <input class="form-control" type="text" id="nombre" name="nombre"  />
					   <span id="snombre"></span>
					</div>
					<div class="col-md-4">
					   <label for="correo">Correo</label>
					   <input class="form-control" type="mail" id="correo" name="correo" />
					   <span id="scorreo"></span>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-8">
					   <label for="telefono">Telefono</label>
					   <input class="form-control" type="text" id="telefono" name="telefono"  />
					   <span id="stelefono"></span>
					</div>

					<div class="col-md-9">
					   <label for="cargo">Cargo</label>
					   <select class="form-control" id="cargo" name="cargo">
							<option value="Doctor">Doctor</option>
							<option value="Enfermera">Enfermera</option>
							
					   </select>
					</div>
				</div>
				
				
				
				<div class="row">
					<div class="col-md-12">
						<hr/>
					</div>
				</div>

				<div class="row mt-3 justify-content-center">
			<div class="col-md-2">
				   <button type="button" class="boton" id="incluir" >Registrar</button>
			</div>
			<div class="col-md-2">	
				   <a href="?pagina=personal" class="boton">Regresar</a>
			</div>
		</div>
			</div>	
	</form>
</div> <!-- fin de container -->


<!-- seccion del modal -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-header text-light bg-info">
        <h5 class="modal-title">Listado de Personal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-content">
	    <!--se agrega un id para poder enlazar con el datatablet--> 
		<table class="table table-striped table-hover" id="tablapersonal">
		<thead>
		  <tr>
			<th>Cedula</th>
			<th>Apellidos</th>
			<th>Nombres</th>
			<th>Correo</th>
			<th>Telefono</th>
			<th>Cargo</th>
		  </tr>
		</thead>
		<tbody id="resultadoconsulta">
		  
		  
		</tbody>
		</table>
    </div>
	<div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
<!--fin de seccion modal-->
<script type="text/javascript" src="js/crearpersonal.js"></script>

</body>
</html>