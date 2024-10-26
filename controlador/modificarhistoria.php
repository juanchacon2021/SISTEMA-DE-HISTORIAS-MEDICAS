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
		  elseif($accion=='eliminar'){
			 $o->set_cedula_historia($_POST['cedula_historia']);
			 echo  json_encode($o->eliminar());
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
			  $o->set_antec_madre($_POST['antec_madre']);
			  $o->set_antec_padre($_POST['antec_padre']);
			  $o->set_antec_hermano($_POST['antec_hermano']);
			  $o->set_cedula_h($_POST['cedula_h']);
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
			  $o->set_extremidades_s($_POST['extremidades_s']);
			  $o->set_neurologicos($_POST['neurologicos']);
			  $o->set_general($_POST['general']); 

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
			$antec_madre = $_POST['antec_madre'];
			$antec_padre = $_POST['antec_padre'];
			$antec_hermano = $_POST['antec_hermano'];
			$cedula_h = $_POST['cedula_h'];
			$boca_abierta = $_POST['boca_abierta'];
			$boca_cerrada = $_POST['boca_cerrada'];
			$oidos = $_POST['oidos'];
			$cabeza_craneo = $_POST['cabeza_craneo'];
			$ojos = $_POST['ojos'];
			$nariz = $_POST['nariz'];
			$tiroides = $_POST['tiroides'];
			$cardiovascular = $_POST['cardiovascular'];
			$respiratorio = $_POST['respiratorio'];
			$abdomen = $_POST['abdomen'];
			$extremidades = $_POST['extremidades'];
			$extremidades_s = $_POST['extremidades_s'];
			$neurologicos = $_POST['neurologicos'];
			$general = $_POST['general'];

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
			$antec_madre,
			$antec_padre,
			$antec_hermano,
			$cedula_h,
			$boca_abierta,
			$boca_cerrada,
			$oidos,
			$cabeza_craneo,
			$ojos,
			$nariz,
			$tiroides,
			$cardiovascular,
			$respiratorio,
			$abdomen,
			$extremidades,
			$extremidades_s,
			$neurologicos,
			$general);
            header('Location: ?pagina=pacientes');
        }
    }
}
