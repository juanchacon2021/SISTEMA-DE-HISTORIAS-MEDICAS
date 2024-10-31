<?php
  
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  if(!empty($_POST)){
		$o = new examenes();   
		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='eliminar'){
			 $o->set_cod_registro($_POST['cod_registro']);
			 echo  json_encode($o->eliminar());
		  }
		  elseif($accion=='incluir'){
				$o->set_nombre_examen($_POST['nombre_examen']);
			  $o->set_descripcion_examen($_POST['descripcion_examen']);
				echo  json_encode($o->incluir());
			  }
		  elseif($accion=='incluir1'){
				$o->set_cedula_h($_POST['cedula_h']);
				$o->set_cod_examenes1($_POST['cod_examenes1']);
				$o->set_fecha_r($_POST['fecha_r']);
				$o->set_observacion_examen($_POST['observacion_examen']);
				echo  json_encode($o->incluir1());
			  }
			  elseif($accion=='modificar'){
				$o->set_cod_registro($_POST['cod_registro']);
				$o->set_cedula_h($_POST['cedula_h']);
				$o->set_cod_examenes1($_POST['cod_examenes1']);
				$o->set_fecha_r($_POST['fecha_r']);
				$o->set_observacion_examen($_POST['observacion_examen']);
				echo  json_encode($o->modificar());
			  }
		  
		  exit;
	  }
	  
	  
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>
