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

			  if($accion=='modificar'){
				echo  json_encode($o->modificar());
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


class PacienteController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cedula_historia = $_POST['cedula_historia'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $fecha_nac = $_POST['fecha_nac'];
			$edad = $_POST['edad'];
			$telefono = $_POST['telefono'];
			$estadocivil = $_POST['estadocivil'];
			$direccion = $_POST['direccion'];
			$ocupacion = $_POST['ocupacion'];
			$hda = $_POST['hda'];
			$habtoxico = $_POST['habtoxico'];
			$alergias = $_POST['alergias'];
			$quirurgico = $_POST['quirurgico'];
			$transsanguineo = $_POST['transsanguineo'];
			$alergias_med = $_POST['alergias_med'];
			$psicosocial = $_POST['psicosocial'];
			$antc_madre = $_POST['antc_madre'];
			$antc_padre = $_POST['antc_padre'];
			$antc_hermano = $_POST['antc_hermano'];

            $this->model->actualizarPaciente($cedula_historia, 
			$nombre, 
			$apellido, 
			$fecha_nac, 
			$edad, 
			$telefono, 
			$estadocivil, 
			$direccion, 
			$ocupacion, 
			$hda, 
			$habtoxico, 
			$alergias, 
			$quirurgico, 
			$transsanguineo, 
			$alergias_med, 
			$psicosocial,
			$antc_madre,
			$antc_padre,
			$antc_hermano,);
            header('Location: ?pagina=pacientes');
        }
    }
}
