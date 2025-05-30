<?php
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  if(!empty($_POST)){
		$o = new personal();   
		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='eliminar'){
			 $o->set_cedula_personal($_POST['cedula_personal']);
			 echo  json_encode($o->eliminar());
			 bitacora::registrar('eliminar', 'Eliminó un personal con cédula: '.$_POST['cedula_personal']);
		  }
		  else{		  
			  $o->set_cedula_personal($_POST['cedula_personal']);
			  $o->set_apellido($_POST['apellido']);
			  $o->set_nombre($_POST['nombre']);
			  $o->set_correo($_POST['correo']);
			  $o->set_telefono($_POST['telefono']);
			  $o->set_cargo($_POST['cargo']);
			  $o->set_clave($_POST['clave']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
				bitacora::registrar('incluir', 'Incluyó un nuevo personal con cédula: '.$_POST['cedula_personal']);
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
				bitacora::registrar('modificar', 'Modificó un personal con cédula: '.$_POST['cedula_personal']);
			  }
		  }
		  exit;
	  }
	  
	  $o = new personal();
	  $datos = $o->consultar(); 
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>
