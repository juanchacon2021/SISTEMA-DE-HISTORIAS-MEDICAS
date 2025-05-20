<?php
if (!is_file("modelo/".$pagina.".php")) {
    echo json_encode(array("resultado"=>"error", "mensaje"=>"Falta definir la clase ".$pagina));
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")) {
    if(!empty($_POST)) {
        $o = new usuarios();   
        $accion = $_POST['accion'];
        
        try {
            switch($accion) {
            // Acciones para usuarios
            case 'consultar_usuarios':
                echo json_encode($o->consultar_usuarios());
                break;
                
            case 'incluir_usuario':
                $o->set_nombre($_POST['nombre']);
                $o->set_email($_POST['email']);
                $o->set_password($_POST['password']);
                $o->set_rol_id($_POST['rol_id']);
                echo json_encode($o->incluir_usuario());
                break;
                
            case 'modificar_usuario':
                $o->set_id($_POST['id']);
                $o->set_nombre($_POST['nombre']);
                $o->set_email($_POST['email']);
                if(!empty($_POST['password'])) {
                    $o->set_password($_POST['password']);
                }
                $o->set_rol_id($_POST['rol_id']);
                echo json_encode($o->modificar_usuario());
                break;
                
            case 'eliminar_usuario':
                $o->set_id($_POST['id']);
                echo json_encode($o->eliminar_usuario());
                break;
                
            // Acciones para roles
            case 'consultar_roles':
                echo json_encode($o->consultar_roles());
                break;
                
            case 'obtener_roles_select':
                echo json_encode($o->obtener_roles_select());
                break;
                
            case 'incluir_rol':
                $o->set_nombre_rol($_POST['nombre']);
                $o->set_descripcion_rol($_POST['descripcion']);
                echo json_encode($o->incluir_rol());
                break;
                
            case 'modificar_rol':
                $o->set_id($_POST['id']);
                $o->set_nombre_rol($_POST['nombre']);
                $o->set_descripcion_rol($_POST['descripcion']);
                echo json_encode($o->modificar_rol());
                break;
                
            case 'eliminar_rol':
                $o->set_id($_POST['id']);
                echo json_encode($o->eliminar_rol());
                break;
                

            case 'consultar_modulos':
                echo json_encode($o->consultar_modulos());
                break;
                
            case 'consultar_permisos_rol':
                echo json_encode($o->consultar_permisos_rol($_POST['rol_id']));
                break;
                
           case 'actualizar_permisos':
                
                $modulos = isset($_POST['modulos']) ? json_decode($_POST['modulos']) : array();
                
        
                if(!is_array($modulos)) {
                    echo json_encode(array("resultado"=>"error", "mensaje"=>"Formato de módulos inválido"));
                    exit;
                }
                
                
                $modulos = array_map('intval', $modulos);
                
                echo json_encode($o->actualizar_permisos($_POST['rol_id'], $modulos));
                break;
                
              default:
                    echo json_encode(array("resultado"=>"error", "mensaje"=>"Acción no válida"));
            }
        } catch(Exception $e) {
            echo json_encode(array(
                "resultado" => "error",
                "mensaje" => "Excepción: " . $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ));
        }
        exit;
    }
    
    require_once("vista/".$pagina.".php"); 
} else {
    echo json_encode(array("resultado"=>"error", "mensaje"=>"Página en construcción"));
}
?>