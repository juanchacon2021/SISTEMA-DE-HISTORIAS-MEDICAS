<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        $accion = $_POST['accion'] ?? '';
        $filtro = $_POST['filtro'] ?? '';
        
        switch($accion){
            case 'consultar':
                $registros = bitacora::consultar($filtro);
                echo json_encode(array(
                    'resultado' => 'exito',
                    'datos' => $registros
                ));
                break;
            default:
                echo json_encode(array(
                    'resultado' => 'error',
                    'mensaje' => 'Acción no válida'
                ));
        }
        exit;
    }
    
    require_once("vista/".$pagina.".php"); 
} else {
    echo "Página en construcción";
}
?>