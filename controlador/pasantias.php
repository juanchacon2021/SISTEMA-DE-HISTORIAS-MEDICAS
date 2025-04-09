<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new pasantias();   
        $accion = $_POST['accion'];
        
        switch($accion){
            // Acciones para estudiantes
            case 'consultar_estudiantes':
                echo json_encode($o->consultar_estudiantes());
                break;
                
            case 'incluir_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_institucion($_POST['institucion']);
                $o->set_telefono($_POST['telefono']);
                $o->set_cod_area($_POST['cod_area']);
                $o->set_fecha_inicio($_POST['fecha_inicio']);
                $o->set_fecha_fin($_POST['fecha_fin']);
                $o->set_activo(isset($_POST['activo']) ? 1 : 0);
                echo json_encode($o->incluir_estudiante());
                break;
                
            case 'modificar_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_institucion($_POST['institucion']);
                $o->set_telefono($_POST['telefono']);
                $o->set_cod_area($_POST['cod_area']);
                $o->set_fecha_inicio($_POST['fecha_inicio']);
                $o->set_fecha_fin($_POST['fecha_fin']);
                $o->set_activo(isset($_POST['activo']) ? 1 : 0);
                echo json_encode($o->modificar_estudiante());
                break;
                
            case 'eliminar_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                echo json_encode($o->eliminar_estudiante());
                break;
                
            // Acciones para áreas
            case 'consultar_areas':
                echo json_encode($o->consultar_areas());
                break;
                
            case 'obtener_areas_select':
                echo json_encode($o->obtener_areas_select());
                break;
                
            case 'obtener_doctores':
                echo json_encode($o->obtener_doctores());
                break;
                
            case 'incluir_area':
                $o->set_nombre_area($_POST['nombre_area']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_responsable_id($_POST['responsable_id']);
                echo json_encode($o->incluir_area());
                break;
                
                case 'modificar_area':
                    $o->set_cod_area($_POST['cod_area']);  // Quitar el apóstrofe simple extra
                    $o->set_nombre_area($_POST['nombre_area']);
                    $o->set_descripcion($_POST['descripcion']);
                    $o->set_responsable_id($_POST['responsable_id']);
                    echo json_encode($o->modificar_area());
                    break;
                
            case 'eliminar_area':
                $o->set_cod_area($_POST['cod_area']);
                echo json_encode($o->eliminar_area());
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