<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new jornadas();   
        $accion = $_POST['accion'];
        
        switch($accion){
            case 'consultar':
                echo json_encode($o->consultar());
                break;
                
            // case 'consultar_jornada':
            //     echo json_encode($o->consultar_jornada($_POST['cod_jornada']));
            //     break;
                
            case 'incluir':
                $o->set_fecha_jornada($_POST['fecha_jornada']);
                $o->set_ubicacion($_POST['ubicacion']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_total_pacientes($_POST['total_pacientes']);
                $o->set_pacientes_masculinos($_POST['pacientes_masculinos']);
                $o->set_pacientes_femeninos($_POST['pacientes_femeninos']);
                $o->set_pacientes_embarazadas($_POST['pacientes_embarazadas']);
                $o->set_cedula_responsable($_POST['cedula_responsable']);
                $o->set_participantes(isset($_POST['participantes']) ? $_POST['participantes'] : array());
                echo json_encode($o->incluir());
                break;
                
            case 'modificar':
                $o->set_cod_jornada($_POST['cod_jornada']);
                $o->set_fecha_jornada($_POST['fecha_jornada']);
                $o->set_ubicacion($_POST['ubicacion']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_total_pacientes($_POST['total_pacientes']);
                $o->set_pacientes_masculinos($_POST['pacientes_masculinos']);
                $o->set_pacientes_femeninos($_POST['pacientes_femeninos']);
                $o->set_pacientes_embarazadas($_POST['pacientes_embarazadas']);
                $o->set_cedula_responsable($_POST['cedula_responsable']);
                $o->set_participantes(isset($_POST['participantes']) ? $_POST['participantes'] : array());
                echo json_encode($o->modificar());
                break;
                
            case 'eliminar':
                $o->set_cod_jornada($_POST['cod_jornada']);
                echo json_encode($o->eliminar());
                break;
                
            case 'obtener_personal':
                echo json_encode($o->obtener_personal());
                break;
                
            case 'obtener_responsables':
                echo json_encode($o->obtener_responsables());
                break;
                
            default:
                echo json_encode(array("resultado"=>"error", "mensaje"=>"Acción no válida"));
        }
        exit;
    }
    
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "Página en construcción";
}
?>