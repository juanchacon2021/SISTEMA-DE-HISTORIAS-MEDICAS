<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

use Shm\Shm\modelo\jornadas;

if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new jornadas();   
        $accion = $_POST['accion'];
        
        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        switch($datos['accion']){
            case 'consultar':
                echo json_encode($o->consultar());
                break;
                
            case 'consultar_jornada':
                echo json_encode($o->consultar_jornada($datos['cod_jornada']));
               break;
                
            case 'incluir':
            case 'modificar':
            case 'eliminar':
                echo json_encode($o->gestionar_jornada($datos));
                $accionTexto = ucfirst($datos['accion']);
                $ubicacion = isset($datos['ubicacion']) ? $datos['ubicacion'] : '';
                bitacora::registrar($accionTexto, 'Acción sobre jornada: '.$ubicacion);
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