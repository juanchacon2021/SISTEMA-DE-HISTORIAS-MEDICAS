<?php

if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  
	  if(!empty($_POST)){
		$o = new historias();   

		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  else{		  
        $o->set_cedula_historia($_POST['cedula_historia']);
        $o->set_nombre($_POST['nombre']);
        $o->set_apellido($_POST['apellido']);
        $o->set_fecha_nac($_POST['fecha_nac']);
        $o->set_edad($_POST['edad']);
        $o->set_estadocivil($_POST['estadocivil']);
        $o->set_ocupacion($_POST['ocupacion']);
        $o->set_direccion($_POST['direccion']);
        $o->set_telefono($_POST['telefono']);
        $o->set_hda($_POST['hda']);
        $o->set_alergias($_POST['alergias']);
        $o->set_alergias_med($_POST['alergias_med']);
        $o->set_quirurgico($_POST['quirurgico']);
        $o->set_transsanguineo($_POST['transsanguineo']);
        $o->set_psicosocial($_POST['psicosocial']);
        $o->set_habtoxico($_POST['habtoxico']);
        $o->set_antc_padre($_POST['antc_padre']);
        $o->set_antc_hermano($_POST['antc_hermano']);
        $o->set_antc_madre($_POST['antc_madre']);
          if($accion=='incluir'){
          echo  json_encode($o->incluir());
          bitacora::registrar('incluir', 'Incluyó una nueva historia clínica con cédula: '.$_POST['cedula_historia']);
          }
          elseif($accion=='modificar'){
          echo  json_encode($o->modificar());
          bitacora::registrar('modificar', 'Modificó una historia clínica con cédula: '.$_POST['cedula_historia']);
          
			  }
		  }
		  exit;
	  }
	  
	  $o = new historias();
	  $datos = $o->consultar(); 
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>