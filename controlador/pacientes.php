<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
    // Desactivar warnings en producción
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_start();

    if(!empty($_POST)){
        $o = new pacientes();   
        $accion = $_POST['accion'];
        
        if($accion=='consultar'){
            while(ob_get_length()) ob_end_clean();
            echo json_encode($o->consultar());  
            exit;
        }
        elseif($accion=='consultarAntecedentes'){
            $o->set_cedula_paciente($_POST['cedula_paciente']);
            while(ob_get_length()) ob_end_clean();
            echo json_encode($o->consultarAntecedentes());
            exit;
        }
        elseif($accion=='agregarFamiliar'){
            $o->set_cedula_paciente($_POST['cedula_paciente'] ?? '');
            $o->set_nom_familiar($_POST['nom_familiar'] ?? '');
            $o->set_ape_familiar($_POST['ape_familiar'] ?? '');
            $o->set_relacion_familiar($_POST['relacion_familiar'] ?? '');
            $o->set_observaciones($_POST['observaciones'] ?? '');
            $resultado = $o->agregarFamiliar();
            while(ob_get_length()) ob_end_clean();
            echo json_encode($resultado);
            exit;
        }
        elseif($accion=='eliminarFamiliar'){
            $o->set_id_familiar($_POST['id_familiar'] ?? 0);
            $resultado = $o->eliminarFamiliar();
            while(ob_get_length()) ob_end_clean();
            echo json_encode($resultado);
            exit;
        }
        else{          
            // Campos básicos del paciente
            $o->set_cedula_paciente($_POST['cedula_paciente'] ?? '');
            $o->set_nombre($_POST['nombre'] ?? '');
            $o->set_apellido($_POST['apellido'] ?? '');
            $o->set_fecha_nac($_POST['fecha_nac'] ?? '');
            $o->set_edad($_POST['edad'] ?? '');
            $o->set_estadocivil($_POST['estadocivil'] ?? '');
            $o->set_ocupacion($_POST['ocupacion'] ?? '');
            $o->set_direccion($_POST['direccion'] ?? '');
            $o->set_telefono($_POST['telefono'] ?? '');
            $o->set_hda($_POST['hda'] ?? '');
            $o->set_alergias($_POST['alergias'] ?? '');
            $o->set_alergias_med($_POST['alergias_med'] ?? '');
            $o->set_quirurgico($_POST['quirurgico'] ?? '');
            $o->set_transsanguineo($_POST['transsanguineo'] ?? '');
            $o->set_psicosocial($_POST['psicosocial'] ?? '');
            $o->set_habtoxico($_POST['habtoxico'] ?? '');

            // Procesar familiares si existen
            if(!empty($_POST['familiares'])) {
                $familiares = json_decode($_POST['familiares'], true);
                if(json_last_error() === JSON_ERROR_NONE) {
                    $o->set_familiares($familiares);
                }
            }
            
            if($accion=='incluir'){
                try {
                    $resultado = $o->incluir();
                    while(ob_get_length()) ob_end_clean();
                    echo json_encode($resultado);
                    exit;
                } catch(Exception $e) {
                    while(ob_get_length()) ob_end_clean();
                    http_response_code(400);
                    echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
                    exit;
                }
            }
            elseif($accion=='modificar'){
                $resultado = $o->modificar();
                while(ob_get_length()) ob_end_clean();
                echo json_encode($resultado);
                exit;
            }
            elseif($accion=='eliminar'){
                $resultado = $o->eliminar();
                while(ob_get_length()) ob_end_clean();
                echo json_encode($resultado);
                exit;
            }
        }
        exit;
    }
    
    $o = new pacientes();
    $datos = $o->consultar(); 
    require_once("vista/".$pagina.".php");
}
else{
    echo "pagina en construccion";
}
?>