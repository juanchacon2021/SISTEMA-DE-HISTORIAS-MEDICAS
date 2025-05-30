<?php

if (!is_file("modelo/".$pagina.".php")){
	
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){

	  
	  if(!empty($_POST)){
		$o = new consultasm();   

		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='listadopersonal'){
			$respuesta = $o->listadopersonal();
			echo json_encode($respuesta);
		}
		elseif($accion=='listadopacientes'){
			$respuesta = $o->listadopacientes();
			echo json_encode($respuesta);
		}
		  elseif($accion=='eliminar'){
			 $o->set_cod_consulta($_POST['cod_consulta']);
			 echo  json_encode($o->eliminar());
			 bitacora::registrar('eliminar', 'Eliminó una consulta médica con código: '.$_POST['cod_consulta']);
		  }
		  else{		  
			  
				$o->set_cod_consulta($_POST['cod_consulta']);
				$o->set_fechaconsulta($_POST['fechaconsulta']);
				$o->set_consulta($_POST['consulta']);
				$o->set_diagnostico($_POST['diagnostico']);
				$o->set_tratamientos($_POST['tratamientos']);
				$o->set_cedula_p($_POST['cedula_p']);
			    $o->set_cedula_h($_POST['cedula_h']);
			    $o->set_boca_abierta($_POST['boca_abierta']);
			    $o->set_boca_cerrada($_POST['boca_cerrada']);
			    $o->set_oidos($_POST['oidos']);
			    $o->set_cabeza_craneo($_POST['cabeza_craneo']);
			    $o->set_ojos($_POST['ojos']);
			    $o->set_nariz($_POST['nariz']);
			    $o->set_respiratorio($_POST['respiratorio']);
			    $o->set_abdomen($_POST['abdomen']);
			    $o->set_extremidades_r($_POST['extremidades_r']);
			    $o->set_extremidades_s($_POST['extremidades_s']);
			    $o->set_neurologicos($_POST['neurologicos']);
			    $o->set_general($_POST['general']);
			    $o->set_cardiovascular($_POST['cardiovascular']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
				bitacora::registrar('incluir', 'Incluyó una nueva consulta médica con código: '.$_POST['cod_consulta']);
				
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
				bitacora::registrar('modificar', 'Modificó una consulta médica con código: '.$_POST['cod_consulta']);
			  }
		  }
		  exit;
	  }
	  
	$o = new consultasm();
	$datos = $o->consultar();
	$datosPacientes = $o->listadopacientes();
	$datosPersonal = $o->listadopersonal();
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>