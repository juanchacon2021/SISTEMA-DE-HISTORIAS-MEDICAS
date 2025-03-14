<html> 
<?php 
	require_once("comunes/encabezado.php"); 
	require_once("comunes/sidebar.php");	
?>

<body>
<?php
	if($nivel=='Doctor'){
?>
<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100">
Pacientes
</div>
<div class="container espacio">
	<div class="container">
		<div class="row mt-3 botones">
			<div class="col-md-2 botonverde" style="cursor: pointer;" >
				<a href="?pagina=historia" style="color: white;">Registrar Paciente</a>
			</div>
					
			<div class="col-md-2 recortar">	
                <a href="?pagina=principal" class="boton">Volver</a>
			</div>
		</div>
	</div>
	<div class="container">
	   <div class="table-responsive">
		<table class="table" id="tablapersonal">
			<thead>
				<tr>
					<th>Acciones</th>
					<th>Cédula</th>
					<th>Apellido</th>
					<th>Nombre</th>
					<th>Fecha de Nacimiento</th>
					<th>Edad</th>
					<th>Teléfono</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($datos as $r): ?>
					<tr>
						<td>
							<div class="button-container" style="display: flex; justify-content: center; gap: 10px; margin-top: 10px">
								<a type="button" class="btn btn-success" href="?pagina=modificarhistoria&&cedula_historia=<?php echo $r['cedula_historia']; ?>" target="_blank">
									<img src="img/lapiz.svg" style="width: 20px">
								</a>
								<a class="btn btn-danger" href="vista/fpdf/historia.php?cedula_historia=<?php echo $r['cedula_historia']; ?>" target="_blank">
									<img src="img/descarga.svg" style="width: 20px;">
								</a>
							</div>
						</td>
						<td><?php echo $r['cedula_historia']; ?></td>
						<td><?php echo $r['apellido']; ?></td>
						<td><?php echo $r['nombre']; ?></td>
						<td><?php echo $r['fecha_nac']; ?></td>
						<td><?php echo $r['edad']; ?></td>
						<td><?php echo $r['telefono']; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	  </div>
  </div>
</div>




<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/pacientes.js"></script>
<?php
	}
?>
</body>
</html>