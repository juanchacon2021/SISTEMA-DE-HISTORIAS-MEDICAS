<?php
  
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  
	  
	  $o = new crear(); 
	  
	  if(!empty($_POST)){
		  
		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='consultatr'){
			 $o->set_cedula_historia($_POST['cedula_historia']); 
			 echo  json_encode($o->consultatr());  
		  }
		  elseif($accion=='obtienefecha'){
			 echo json_encode($o->obtienefecha());
		  }
		  elseif($accion=='eliminar'){
		  }
		  else{		  
			  $o->set_cedula_historia($_POST['cedula_historia']);
			  $o->set_apellido($_POST['apellido']);
			  $o->set_nombre($_POST['nombre']);
			  $o->set_fecha_nac($_POST['fecha_nac']);
			  $o->set_edad($_POST['edad']);
			  $o->set_telefono($_POST['telefono']);
			  $o->set_estadocivi($_POST['estadocivi']);
			  $o->set_direccion($_POST['direccion']);
			  $o->set_ocupacion($_POST['ocupacion']);
			  $o->set_hda($_POST['hda']);
			  $o->set_habtoxico($_POST['habtoxico']);
			  $o->set_alergias($_POST['alergias']);
			  $o->set_quirurgico($_POST['quirurgico']);
			  $o->set_transsanguineo($_POST['transsanguineo']);
			  $o->set_boca_abierta($_POST['boca_abierta']);
			  $o->set_boca_cerrada($_POST['boca_cerrada']);
			  $o->set_oidos($_POST['oidos']);
			  $o->set_cabeza_craneo($_POST['cabeza_craneo']);
			  $o->set_ojos($_POST['ojos']);
			  $o->set_nariz($_POST['nariz']);
			  $o->set_tiroides($_POST['tiroides']);
			  $o->set_cardiovascular($_POST['cardiovascular']);
			  $o->set_respiratorio($_POST['respiratorio']);
			  $o->set_abdomen($_POST['abdomen']);
			  $o->set_extremidades($_POST['extremidades']);
			  $o->set_neurologico($_POST['neurologico']);
			//   $o->set_general($_POST['general']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			  }
		  }
		  exit;
	  }
	  
	  
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }