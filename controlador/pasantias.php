<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

use Shm\Shm\modelo\pasantias;
if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new pasantias();   
        $accion = $_POST['accion'];
        
        switch($accion){
            case 'consultar_estudiantes':
                echo json_encode($o->consultar_estudiantes());
                break;
            
            case 'consultar_asistencia':
                echo json_encode($o->consultar_asistencia());
                break;

            case 'incluir_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_institucion($_POST['institucion']);
                $o->set_telefono($_POST['telefono']);
                if(isset($_POST['cod_area'])) {
                    $o->set_cod_area($_POST['cod_area']);
                }
                echo json_encode($o->incluir_estudiante());
                bitacora::registrar('Registrar', 'Se ha registrado un estudiante con cédula: '.$_POST['cedula_estudiante']);
                break;
                
            case 'modificar_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_institucion($_POST['institucion']);
                $o->set_telefono($_POST['telefono']);
                echo json_encode($o->modificar_estudiante());
                bitacora::registrar('Modificar', 'Se ha modificado un estudiante con cédula: '.$_POST['cedula_estudiante']);
                break;
                
            case 'eliminar_estudiante':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                echo json_encode($o->eliminar_estudiante());
                bitacora::registrar('Eliminar', 'Se ha eliminado un estudiante con cédula: '.$_POST['cedula_estudiante']);
                break;

            case 'incluir_asistencia':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_cod_area($_POST['cod_area']);
                $o->set_fecha_inicio($_POST['fecha_inicio']);
                $o->set_fecha_fin($_POST['fecha_fin']);
                $o->set_activo(isset($_POST['activo']) ? 1 : 0);
                echo json_encode($o->incluir_asistencia());
                bitacora::registrar('Registrar', 'Se ha registrado asistencia para estudiante: '.$_POST['cedula_estudiante']);
                break;
                
            case 'modificar_asistencia':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_cod_area($_POST['cod_area']);
                $o->set_fecha_inicio($_POST['fecha_inicio']);
                $o->set_fecha_fin($_POST['fecha_fin']);
                $o->set_activo(isset($_POST['activo']) ? 1 : 0);
                echo json_encode($o->modificar_asistencia());
                bitacora::registrar('Modificar', 'Se ha modificado asistencia para estudiante: '.$_POST['cedula_estudiante']);
                break;
                
            case 'eliminar_asistencia':
                $o->set_cedula_estudiante($_POST['cedula_estudiante']);
                $o->set_fecha_inicio($_POST['fecha_inicio']);
                echo json_encode($o->eliminar_asistencia());
                bitacora::registrar('Eliminar', 'Se ha eliminado una asistencia del estudiante con cédula: '.$_POST['cedula_estudiante']);
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
                $o->set_cedula_personal($_POST['responsable_id']);
                echo json_encode($o->incluir_area());
                bitacora::registrar('Registrar', 'Se ha registrado un área con nombre: '.$_POST['nombre_area']);
                break;
                
            case 'modificar_area':
                $o->set_cod_area($_POST['cod_area']);
                $o->set_nombre_area($_POST['nombre_area']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_cedula_personal($_POST['responsable_id']);
                echo json_encode($o->modificar_area());
                bitacora::registrar('Modificar', 'Se ha modificado un área con código: '.$_POST['cod_area']);
                break;
                
            case 'eliminar_area':
                $o->set_cod_area($_POST['cod_area']);
                echo json_encode($o->eliminar_area());
                bitacora::registrar('Eliminar', 'Se ha eliminado un área con código: '.$_POST['cod_area']);
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