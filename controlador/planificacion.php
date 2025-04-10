
<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new planificacion();   
        $accion = $_POST['accion'];
        
        switch($accion){
            case 'guardarPublicacion':
                $contenido = $_POST['contenido'] ?? '';
                $imagen = $_FILES['imagen'] ?? null;
                $cedula_p = $_SESSION['usuario'] ?? null; // Asumiendo que la cédula está en la sesión
                
                if(!$cedula_p) {
                    echo json_encode(array("resultado" => "error", "mensaje" => "No se pudo identificar al usuario"));
                    exit;
                }
                
                echo json_encode($o->guardarPublicacion($cedula_p, $contenido, $imagen));
                break;
                
            case 'modificarPublicacion':
                $cod_pub = $_POST['cod_pub'] ?? null;
                $contenido = $_POST['contenido'] ?? '';
                $imagen = $_FILES['imagen'] ?? null;
                
                if(!$cod_pub) {
                    echo json_encode(array("resultado" => "error", "mensaje" => "No se especificó la publicación a modificar"));
                    exit;
                }
                
                echo json_encode($o->modificarPublicacion($cod_pub, $contenido, $imagen));
                break;
                
            case 'eliminarPublicacion':
                $cod_pub = $_POST['cod_pub'] ?? null;
                
                if(!$cod_pub) {
                    echo json_encode(array("resultado" => "error", "mensaje" => "No se especificó la publicación a eliminar"));
                    exit;
                }
                
                echo json_encode($o->eliminarPublicacion($cod_pub));
                break;
                
            case 'obtenerPublicaciones':
                echo json_encode($o->obtenerPublicaciones());
                break;
                
            case 'obtenerPublicacion':
                $cod_pub = $_POST['cod_pub'] ?? null;
                
                if(!$cod_pub) {
                    echo json_encode(array("resultado" => "error", "mensaje" => "No se especificó la publicación"));
                    exit;
                }
                
                echo json_encode($o->obtenerPublicacion($cod_pub));
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