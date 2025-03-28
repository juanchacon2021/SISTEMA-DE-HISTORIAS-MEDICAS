<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	$o = new historias();   
	  if(!empty($_POST)){
		  $accion = $_POST['accion'];
		  if($accion=='consultar'){
			 echo json_encode($o->consultar());  
		  }
		  else{		  
			  $o->set_cedula_historia($_POST['cedula_historia']);
			  $o->set_apellido($_POST['apellido']);
			  $o->set_nombre($_POST['nombre']);
			  $o->set_fecha_nac($_POST['fecha_nac']);
			  $o->set_edad($_POST['edad']);
			  $o->set_telefono($_POST['telefono']);
			  $o->set_estadocivil($_POST['estadocivil']);
			  $o->set_direccion($_POST['direccion']);
			  $o->set_ocupacion($_POST['ocupacion']);
			  $o->set_hda($_POST['hda']);
			  $o->set_habtoxico($_POST['habtoxico']);
			  $o->set_alergias($_POST['alergias']);
			  $o->set_quirurgico($_POST['quirurgico']);
			  $o->set_transsanguineo($_POST['transsanguineo']);
			  $o->set_alergias_med($_POST['alergias_med']);
			  $o->set_psicosocial($_POST['psicosocial']);
			  $o->set_antc_madre($_POST['antc_madre']);
			  $o->set_antc_padre($_POST['antc_padre']);
			  $o->set_antc_hermano($_POST['antc_hermano']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			  }
		  }
		  exit;
	  }  else {
		if (isset($_GET['cedula_historia'])) { // Por ejemplo, si pasas la cédula por GET
		  $datos = $o->obtenerPorCedula($_GET['cedula_historia']); // Llama al modelo
		}
		require_once("vista/historia.php"); 
	  }
	  
	  
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>
