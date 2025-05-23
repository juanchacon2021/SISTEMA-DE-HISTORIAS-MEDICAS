<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")) {
    // Instanciamos el objeto fuera del if POST
    $o = new planificacion();
    
    if(!empty($_POST)){
        // Asignar valores
        if(isset($_POST['cod_pub'])) $o->set_cod_pub($_POST['cod_pub']);
        if(isset($_POST['contenido'])) $o->set_contenido($_POST['contenido']);
        if(isset($_FILES['imagen'])) $o->set_imagen($_FILES['imagen']);
        
        $o->set_id_usuario($_SESSION['usuario']); // Usuario actual
        
        $accion = $_POST['accion'];

        switch($accion) {
            case 'consultar_publicaciones':
                echo json_encode($o->consultar_publicaciones());
                exit;
            case 'obtener_publicacion':
                echo json_encode($o->obtener_publicacion());
                exit;
            case 'incluir_publicacion':
                echo json_encode($o->incluir());
                bitacora::registrar('Registrar', 'Se ha registrado una publicación');
                exit;
            case 'modificar_publicacion':
                echo json_encode($o->modificar());
                bitacora::registrar('Modificar', 'Se ha modificado una publicación');
                exit;
            case 'eliminar_publicacion':
                // Verificar propiedad primero
                $co = $o->conecta();
                $verificar = $co->query("SELECT id_usuario FROM feed WHERE cod_pub = '".$o->get_cod_pub()."'");
                $publicacion = $verificar->fetch(PDO::FETCH_ASSOC);

                if(!$publicacion || $publicacion['id_usuario'] != $o->get_id_usuario()) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'No tienes permisos para esta acción'
                    ]);
                    exit;
                }
                
                echo json_encode($o->eliminar());
                bitacora::registrar('Eliminar', 'Se ha eliminado una publicación');
                exit;
        }
    }
    
    // Cargar datos iniciales para la vista (fuera del if POST)
    $o->set_id_usuario($_SESSION['usuario']);
    $publicaciones = $o->consultar_publicaciones();
    
    require_once("vista/".$pagina.".php");
}
else{
    echo "Página en construcción";
}
?>