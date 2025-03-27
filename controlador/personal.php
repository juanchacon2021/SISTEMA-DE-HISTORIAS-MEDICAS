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
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			  }
		  }
		  exit;
	  }
	  class Controlador{
		private $modelo;
		public function __construct()
		{
			$this->modelo = new personal();
		}

		public function consultar(){
			return $this->modelo->consultar();
		}
	  }
	  $controlador = new Controlador();
	  $datos= $controlador->consultar();
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>
